<template>
    <div>
        <section>
			<div class="container tasks">
                <div class="row tasks_item" v-for="document in documents" :key="document.id">
                    <div class="col-lg-1">
                        <input type="checkbox" class="form-control" :value="document.id" v-model="remove_files">
                    </div>
                    <div class="col-lg-1 file-ext">
                        <i :class="'far fa-' + getExtention(document.link)"></i>
                    </div>
					<div class="col-lg-10">
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
    import { mapState } from 'vuex';
    export default {
        data(){
            return {
                uploaded_doc: '',
                isHidden: false,
                remove_files: [],
            };
        },
        mounted() {
		},
        computed: {
            ...mapState({
                documents: state => state.tasks.documents,
                error: state => state.tasks.error,
                errors: state => state.tasks.errors,
                add_document_success: state => state.tasks.add_document_success,
            }),
        },
        methods: {
            handleFilesUpload() {
                this.uploaded_doc = this.$refs.document.files;
            },
            addDocument() {
                var formData = new FormData();
                for( var i = 0; i < this.uploaded_doc.length; i++ ){
                  let file = this.uploaded_doc[i];
                  formData.append('files[' + i + ']', file);
                }
                this.$store.dispatch('addDocument', {
                    formData: formData,
                    task_id: this.$route.params.id
                });
            },
            removeDocument() {
                this.$store.dispatch('removeDocument', {
                    data: {
                        documents: this.remove_files,
                    },
                    task_id: this.$route.params.id
                }) 
                this.remove_files = []; 
            },
            getExtention(value) {
                let fileExt = value.split('.').pop().toLowerCase();
                switch (fileExt) {
                    case 'doc':
                    case 'docx':
                        return 'file-word';
                        break;
                    case 'xls':
                    case 'xlsx':
                        return 'file-excel';
                        break;
                    case 'jpeg':
                    case 'png':
                    case 'psd':
                        return 'file-image';
                        break;
                    case 'pdf':
                        return 'file-pdf';
                        break;
                    default: 
                        return 'file';
                }
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