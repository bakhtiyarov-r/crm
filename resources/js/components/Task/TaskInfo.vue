<template>
	<div>
		<section>
			<div class="container">
				<div class="row content">
					<div class="col-md-8 col-lg-8">
						<div>
							<span class="task_title">Статус:</span>
							<span class="task_value">{{task.opened | status}}</span>
							<span class="task_value">({{task.done | doneFilter}})</span>
						</div>
						<div v-if="task.canceled"> 
							<span class="task_title">Отменено</span>
						</div>
						<div>
							<span class="task_title">Создана:</span>
							<span class="task_value">{{task.created_at | date}}</span>
						</div>
						<div>
							<span class="task_title">Крайний срок:</span>
							<span class="task_value">{{task.deadline | date}}</span>
						</div>
						<div v-if="task.immediate">
							<span class="task_title">Срочное</span>
						</div>
						<div v-if="task.drafts"> 
							<span class="task_title">В черновике</span>
						</div>
						<div>
							<span class="task_title">Автор:</span>
							<span class="task_value" v-if="task.user">{{task.user.name}} {{task.user.profile.surname}}</span>
						</div>
						<div style="display: flex;">
			              <span class="task_title">Ответственные:</span>
			              <div>
			                <span class="task_value" v-for="executor in task.executors" :key="executor.id" v-if="task.executors">{{executor.name}} {{executor.profile.surname}}<br></span>
			              </div>
			            </div>
						<div>
							<span class="task_title">Описание:</span>
							<span class="task_value">{{task.description}}</span>
						</div>
					</div>
					<div class="action__btns">
						<button-orange v-if="task.opened && task.done" btnClass="btn_orange task_close" @click.native="closeTask" btnTitle="Закрыть"></button-orange>
						<button-orange v-if="task.opened" btnClass="btn_orange task_done" @click.native="doneTask" btnTitle="На проверку/На доработку"></button-orange>
						<button-orange btnClass="btn_orange task_edit" @click.native="isHidden = true" btnTitle="Править"></button-orange>
						<button-orange btnClass="btn_orange task_delete" @click.native="deleteTask" btnTitle="Удалить"></button-orange>
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Редактировать задачу</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !edit_task_success">
                <p>При редактировании произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="edit_task_success">
                <p>Задача отредактирована</p>
            </div>
            <form autocomplete="off" @submit.prevent="editTask" method="post">
                <div class="form-group form-group_half-width">
                    <label for="task_name">Название</label>
                    <input type="text" id="task_name" class="form-control" v-model="task.title" required>
                </div>
                <div class="form-group form-group_half-width">
                    <label for="deadline">Срок выполнения</label>
                    <input type="date" id="deadline" class="form-control" v-model="task.deadline">
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="task_description">Описание</label>
                    <textarea type="text" id="task_description" class="form-control" v-model="task.description">
                    </textarea> 
                </div>
                <div class="form-group form-group_half-width">
                	<div class="form-sub-group">
	                    <label for="canceled">Открыто</label>
	                    <input type="checkbox" id="done" class="form-control" v-model="task.opened">
	                </div>
	                <div class="form-group" style="width: 100%;">
	                  Ответственые:
	                  <div v-for="user in users" :key="user.id" style="display:flex;width:200px;justify-content: space-between;align-items: center;">
	                    <label :for="user.id">{{user.name}} {{user.profile.surname}}</label>
	                    <input type="checkbox" :id="user.id" class="form-control" :value="user.id" v-model="responsible" checked>
	                  </div>
	                </div>
                </div>
                <div class="form-group form-group_half-width">
	                <div class="form-sub-group">
	                    <label for="immediate">Срочное</label>
	                    <input type="checkbox" id="immediate" class="form-control" v-model="task.immediate" >
	                </div>
	                <div class="form-sub-group">
	                    <label for="canceled">Отменить</label>
	                    <input type="checkbox" id="canceled" class="form-control" v-model="task.canceled">
	                </div>
	                <div class="form-sub-group">
	                    <label for="drafts">В черновик</label>
	                    <input type="checkbox" id="drafts" class="form-control" v-model="task.drafts">
	                </div>
                </div>
                <button type="submit" class="btn btn-default" >Отправить</button>
            </form>
        </div>
        <div v-show="isHidden" class="overlay">
        </div>
	</div>
</template>
<script>
	import { mapState } from 'vuex';
  export default {
  	data() {
  		return {
  			isHidden: false,
  		}
  	},
  	mounted() {
	},
	computed: {
		...mapState({
    		task: state => state.tasks.item,
    		error: state => state.tasks.error,
	        errors: state => state.tasks.errors,
	        edit_task_success: state => state.tasks.edit_success,
    		users: state => state.users.items,
    	}),
    	responsible: {
	        get () {
	          return this.$store.state.tasks.executors
	        },
	        set (value) {
	          this.$store.commit('addExecutors', value)
	        }
	      },
	},
  	methods: {
  		closeTask() {
  			this.$store.dispatch('closeTask', {
		            data: {
						opened: !this.task.opened,
	  					done: false
		            },
		            task_id: this.$route.params.id,
	        	},
	        );
  		},
  		doneTask() {
  			this.$store.dispatch('doneTask', {
		            data: {
						done: !this.task.done
		            },
		            task_id: this.$route.params.id,
	        	},
	        ); 
  		},
  		editTask() {
  			this.$store.dispatch('editTask', {
		            data: {
						title: this.task.title,
			  			description: this.task.description,
			  			deadline: this.task.deadline,
			  			immediate: this.task.immediate,
			  			drafts: this.task.drafts,
			  			canceled: this.task.canceled,
			  			opened: this.task.opened,
			  			responsible: this.responsible
		            },
		            task_id: this.$route.params.id,
	        	},
	        );
        	this.responsible;
  		},
	  	deleteTask() {
	  		var confirmDelete = confirm("Удалить задачу и связанные документы?");
	        if (confirmDelete) {
	          this.$store.dispatch('deleteTask', {
	              task_id: this.$route.params.id
	            }
	          );
	        }
	  	}
  	},
  	filters: {
  		boolToString(value) {
  			if (!value) return 'Нет';
  			return 'Да';
  		},
  		status(value) {
  			if (!value) return 'Закрыто';
  			return 'Открыто';
  		},
  		doneFilter(value) {
  			if (!value) return 'в работе';
  			return 'на проверке';
  		},
  		date(value) {
  			if (!value) return;
  			return value.split('-').reverse().join('.');
  		}
  	}
  }
</script>
