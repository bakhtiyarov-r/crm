// resources/assets/js/views/Company.vue
<template>
	<div>
		<section>
			<div class="container filter">
				<div class="row">
					<div class="col-lg-3">
						<div class="filter_search">
							<input type="text" name="search"  placeholder="Поиск по имени" v-model="user_name_filter">
						</div>
					</div>
					<div class="col-lg-9">
						<div class="filter_checkbox">
							          
						</div>     	
						<div class="filter_name" >
							<select v-model="user_position_filter">
								<option value="all">Все</option>
								<option v-for="user in users" v-bind:value="user.id">{{ user.department.title }}</option>
							</select>
							<!-- <a href="#" v-for="author in tasks.tasks">{{ author.user.name }}&nbsp;{{ author.user.profile.surname }}&nbsp;&nbsp;</a> -->
						</div>
					</div> 
				</div>  	
			</div>
			<div class="action__btns">
	            <button-orange v-if="this.$auth.user().role_id == 4 || this.$auth.user().sudo" btnClass="btn_orange task_add" @click.native="isHidden = true" btnTitle="Добавить"></button-orange>
			</div>
		</section>
		<section>
			<div class="container tasks">
				<div class="row tasks_items">
					<div class="col-lg-3">
						Сотрудник
					</div>
					<div class="col-lg-6">
						Должность
					</div>
					<div class="col-lg-3">
						Дата принятия
					</div>
				</div>
				<div class="row tasks_item" v-for="user in usersList" :key="user.id">
					<div class="col-lg-3">
						<a :href="'/users/' + user.id">{{user.name}} {{user.profile.surname}}</a>
					</div>
					<div class="col-lg-6">
						{{user.profile.position}}
					</div>
					<div class="col-lg-3">
						{{user.created_at | date}}
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Добавить  сотрудника</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !add_success">
                <p>При добавлении произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="add_success">
                <p>Сотрудник добавлен</p>
            </div>
            <form autocomplete="off" @submit.prevent="addUser" method="post">
                <div class="form-group form-group_half-width">
                    <label for="title">Имя</label>
                    <input type="text" id="title" class="form-control" v-model="name" required>
                </div>
                
                <div class="form-group" style="width: 100%;">
                    <label for="surname">Фамилия</label>
                    <input type="text" id="surname" class="form-control" v-model="surname">
                    
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="position">Должность</label>
                    <input type="text" id="position" class="form-control" v-model="position">
                    
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="birthday">День рождения</label>
                    <input type="date" id="birthday" class="form-control" v-model="birthday">
                    
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" class="form-control" v-model="phone">
                    
                </div>
                <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" class="form-control" v-model="password" required>
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
  			name: 'Новый сотрудник',
  			surname: '',
  			email: '',
  			phone: '',
  			password: '',
  			birthday: '',
  			position: '',
        isHidden: false,
        user_name_filter: '',
        user_position_filter: 'all',
  		}
  	},
  	mounted() {
      this.$store.dispatch('getUsers');
    },
  	methods: {
  		addUser() {
        this.$store.dispatch('addUser', {
            data: {
              name: this.name,
              surname: this.surname,
              email: this.email,
              phone: this.phone,
              position: this.position,
              birthday: this.birthday,
              password: this.password
            }
          },
        );
	  	},
  	},
    computed: {
        ...mapState({
          users: state => state.users.items,
          error: state => state.users.error,
          errors: state => state.users.errors,
          add_success: state => state.users.add_success,
        }),
        usersList() {
        	var app = this;
            var userName = this.user_name_filter.toLowerCase();
            if (app.users) {
            	return app.users.filter(elem => (elem.name + ' ' + elem.profile.surname).toLowerCase().indexOf(userName) > -1)
            	.filter(elem => {
            		if(app.user_position_filter == 'all') {
	        			return true;
	        		} else {
	        			return elem.id == app.user_position_filter;
	        		}
	        	})
          }
        }
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

