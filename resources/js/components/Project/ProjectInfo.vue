// resources/assets/js/views/ProjectInfo.vue

<template>
	<div>
		<section>
			<div class="container">
				<div class="row content">
					<div class="col-md-9 col-lg-10">
						<div>
							<span class="task_title">Создан:</span>
							<span class="task_value">{{project.created_at | date}}</span>
						</div>
						<div>
							<span class="task_title">Статус:</span>
							<span class="task_value">{{project.opened | status}}</span>
						</div>
						<div>
							<span class="task_title">Менеджер:</span>
							<span class="task_value" v-if="project.user">{{project.user.name}} {{project.user.profile.surname}}</span>
						</div>
            <div style="display: flex;">
              <span class="task_title">Ответственные:</span>
              <div>
                <span class="task_value" v-for="executor in project.executors" :key="executor.id" v-if="project.executors">{{executor.name}} {{executor.profile.surname}}<br></span>
              </div>
            </div>
						<div>
							<span class="task_title">Описание:</span>
							<span class="task_value">{{project.description}}</span>
						</div>
					</div>
					<div class="action__btns">
            <button-orange btnClass="btn_orange task_edit" @click.native="isHidden = true" btnTitle="Править проект"></button-orange>
            <button-orange btnClass="btn_orange task_delete" @click.native="deleteProject" btnTitle="Удалить проект"></button-orange>
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Редактировать проект</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !edit_success">
                <p>При редактировании произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="edit_success">
                <p>Проект отредактирован</p>
            </div>
            <form autocomplete="off" @submit.prevent="editProject" method="post">
                <div class="form-group form-group_half-width">
                    <label for="task_name">Название</label>
                    <input type="text" id="task_name" class="form-control" v-model="project.title" required>
                </div>
                <div class="form-group form-group_half-width">
                    <label for="deadline">Срок выполнения</label>
                    <input type="date" id="deadline" class="form-control" v-model="project.deadline">
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="task_description">Описание</label>
                    <textarea type="text" id="task_description" class="form-control" v-model="project.description">
                    </textarea> 
                </div>
                <div class="form-group" style="width: 100%;">
                  Ответственые:
                  <div v-for="user in users" :key="user.id" style="display:flex;width:200px;justify-content: space-between;align-items: center;">
                    <label :for="user.id">{{user.name}} {{user.profile.surname}}</label>
                    <input type="checkbox" :id="user.id" class="form-control" :value="user.id" v-model="responsible" checked>
                  </div>
                </div>
                <div class="form-group form-group_half-width">
                  <div class="form-sub-group">
                      <label for="opened">Открыто</label>
                      <input type="checkbox" id="opened" class="form-control" v-model="project.opened">
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
  	methods: {
  		editProject() {
	        this.$store.dispatch('editProject', {
	            data: {
					title: this.project.title,
					description: this.project.description,
					opened: this.project.opened,
					responsible: this.responsible
	            },
	            	project_id: this.$route.params.id,
	        	},
	        );
        	this.responsible;
  		},
	  	deleteProject() {
	        var confirmDelete = confirm("Удалить проект и связанные задачи?");
	        if (confirmDelete) {
	          this.$store.dispatch('deleteProject', {
	              project_id: this.$route.params.id
	            }
	          );
	        }
	  	}
  	},
    computed: {
      responsible: {
        get () {
          return this.$store.state.projects.responsible
        },
        set (value) {
          this.$store.commit('addResponsible', value)
        }
      },
      ...mapState({
          project: state => state.projects.item,
          error: state => state.projects.error,
          errors: state => state.projects.errors,
          edit_success: state => state.projects.edit_success,
          users: state => state.users.items,
        }),
    },
  	filters: {
  		status(value) {
  			if (!value) return 'Закрыто';
  			return 'Открыто';
  		},
      date(value) {
        if (!value) return;
        return value.split('-').reverse().join('.');
      }
  	}
  }
</script>
