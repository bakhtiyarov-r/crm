<template>
    <div>
        <section>
			<div class="container tasks">
                <div class="row tasks_item" v-for="document in documents" :key="document.id">
                    <div class="col-lg-1">
                        <input type="checkbox" class="form-control" :value="document.id" v-model="remove_files">
                    </div>
					<div class="col-lg-11">
						<a :href="'/storage/' + document.link">{{document.doc_name}}</a>
					</div>
				</div>
			</div>
            <div class="action__btns">
                <button-orange btnClass="btn_orange task_add" @click.native="isHidden = true" btnTitle="Добавить"></button-orange>
                <button-orange btnClass="btn_orange task_delete" @click.native="removeDocument()" btnTitle="Добавить"></button-orange>
            </div>
        </section>
        <div v-show="isHidden" class="taskadd__form">
            <div class="taskadd__form_close" @click="isHidden = false">X</div>
            <div class="title">
                <h2 >Добавить документ</h2>
            </div>
            <div class="alert alert-danger" v-if="error && !add_document_success">
                <p>При добавлении произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="add_document_success">
                <p>Документ добавлен</p>
            </div>
            <div>
                <div class="form-group" >
                    <input type="file" multiple class="form-control-file" ref="document"  v-on:change="handleFilesUpload()" id="docFile" aria-describedby="fileHelp">
                    <span class="help-block" v-if="error && errors.document">{{ errors.avatar }}</span>
                </div>
                    
                <button type="submit" class="btn btn-default" v-on:click="addDocument()">Отправить</button>
            </div>
        </div>
        <div v-show="isHidden" class="overlay">
        </div>
    </div>
</template>
<script> 
    export default {
        data(){
            return {
            	documents: [],
                uploaded_doc: '',
                isHidden: false,
                remove_files: [],
                add_document_success: false,
                error: false,
                errors: {}
            };
        },
        mounted() {
		  	this.getTask();
		},
        methods: {
        	getTask() {
	  			var app = this;
		  		this.axios.get('tasks/' + this.$route.params.id).then(response => {
		  			app.documents = response.data.data.documents;
	            }).catch(error => {
	                app.error = true;
	                app.errors = error.data;
	            });  
		  	},
            addDocument() {
                var app = this;
                var formData = new FormData();
                for( var i = 0; i < this.uploaded_doc.length; i++ ){
				  let file = this.uploaded_doc[i];
				  formData.append('files[' + i + ']', file);
				}

                this.axios.post(this.$route.params.id + '/document', formData, {
                    headers: {
                      'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                	this.getTask();
                    app.add_document_success = true;
                    app.success = true
                }).catch(error => {
                    app.error = true;
                    app.errors = resp.response.data.errors;
                });    
            },
            handleFilesUpload() {
                this.uploaded_doc = this.$refs.document.files;
            },
            removeDocument() {
                var app = this;
                this.axios.put(this.$route.params.id + '/document', {
                    documents: app.remove_files
                }).then(response => {
                    this.getTask();
                }).catch(error => {
                    app.error = true;
                    app.errors = resp.response.data.errors;
                });
            }
        },
        filters: {
            date(value) {
                if (!value) return;
                return value.split('-').reverse().join('.');
            }
        }
    }
</script>