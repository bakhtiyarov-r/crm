// resources/assets/js/views/Company.vue
<template>
	<div>
		<section>
			<div class="container filter">
			</div>
			<div class="action__btns">
	            <button-orange btnClass="btn_orange task_add" @click.native="isHidden = true" btnTitle="Добавить"></button-orange>
			</div>
		</section>
		<section>
			<div class="container tasks">
				<div class="row tasks_items">
					<div class="col-lg-3">
						Название отдела
					</div>
					<div class="col-lg-6">
						Описание
					</div>
					<div class="col-lg-3">
						
					</div>
				</div>
				<div class="row tasks_item" v-for="department in departments" :key="department.id">
					<div class="col-lg-3">
						{{department.title}}</a>
					</div>
					<div class="col-lg-6">
						{{department.description}}
					</div>
					<div class="col-lg-3">
						
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Добавить отдел</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !add_success">
                <p>При добавлении произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="add_success">
                <p>Отдел добавлен</p>
            </div>
            <form autocomplete="off" @submit.prevent="addDepartment" method="post">
                <div class="form-group form-group_half-width">
                    <label for="title">Название отдела</label>
                    <input type="text" id="title" class="form-control" v-model="title" required>
                </div>
                
                <div class="form-group" style="width: 100%;">
                    <label for="surname">Описание</label>
                    <input type="text" id="description" class="form-control" v-model="description">
                </div>
                <button type="submit" class="btn btn-default" >Добавить</button>
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
  			title: 'Новый отдел',
  			description: '',
        isHidden: false,
  		}
  	},
  	mounted() {
      this.$store.dispatch('getDepartments');
    },
  	methods: {
  		addDepartment() {
        this.$store.dispatch('addDepartment', {
            data: {
              title: this.title,
              description: this.description,
            }
          },
        );
	  	},
  	},
    computed: {
        ...mapState({
          users: state => state.users.items,
          departments: state => state.departments.items,
          error: state => state.departments.error,
          errors: state => state.departments.errors,
          add_success: state => state.departments.add_success,
        }),
        // usersList() {
        // 	var app = this;
        //     var userName = this.user_name_filter.toLowerCase();
        //     if (app.users) {
        //     	return app.users.filter(elem => (elem.name + ' ' + elem.profile.surname).toLowerCase().indexOf(userName) > -1)
        //     	.filter(elem => {
        //     		if(app.user_position_filter == 'all') {
	       //  			return true;
	       //  		} else {
	       //  			return elem.id == app.user_position_filter;
	       //  		}
	       //  	})
        //   }
        // }
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

