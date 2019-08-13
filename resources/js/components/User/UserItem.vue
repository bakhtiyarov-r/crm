<template>
    <div>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title">
                          <h2>{{ user.name }} {{ user.profile.surname }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row divider">
                    <div class="col-md-9 col-lg-10">
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <button-orange btnClass="btn_orange task_edit" @click.native="isHidden = true" btnTitle="Править"></button-orange>
                        <button-orange btnClass="btn_orange task_delete" @click.native="deleteUser" btnTitle="Удалить"></button-orange>
                    </div>
                </div>
                <div class="row content">
                    <div class="col-md-4 col-lg-4">
                        <img  :src="'/storage/avatars/' + user.profile.avatar" style="width: 100%;">
                    </div>
                    <div class="col-md-8 col-lg-8">
                        <div v-if="user.department">
                            <span class="task_title">Отдел:</span>
                            <span class="task_value">{{user.department.title}}</span>
                        </div>
                        <div>
                            <span class="task_title">Должность:</span>
                            <span class="task_value">{{user.profile.position}}</span>
                        </div>
                        <div>
                            <span class="task_title">Дата приема:</span>
                            <span class="task_value">{{user.created_at | date}}</span>
                        </div>
                        <div>
                            <span class="task_title">День рождения:</span>
                            <span class="task_value">{{user.profile.birthday | date}}</span>
                        </div>
                        <div>
                            <span class="task_title">Телефон:</span>
                            <span class="task_value">{{user.profile.phone}} </span>
                        </div>
                        <div>
                            <span class="task_title">Email:</span>
                            <span class="task_value">{{user.email}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div v-show="isHidden" class="taskadd__form column__form">
            <div class="taskadd__form_close" @click="isHidden = false">X</div>
            <div class="title">
                <h2 >Редактировать профиль</h2>
            </div>
            <div class="alert alert-danger" v-if="error && !success">
                <p>При изменении произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="success">
                <p>Данные успешно изменены</p>
            </div>
            <form autocomplete="off" @submit.prevent="update" method="post">
                <div class="form-row">
                    <div class="form-group input-group-sm col-sm-6" v-bind:class="{ 'has-error': error && errors.user.name }">
                        <label for="name">Имя</label>
                        <input type="text" id="name" class="form-control" v-model="user.name" required>
                        <span class="help-block" v-if="error && errors.user.name">{{ errors.user.name }}</span>
                    </div>
                    <div class="form-group input-group-sm col-sm-6" v-bind:class="{ 'has-error': error && errors.user.profile.surname }">
                        <label for="surname">Фамилия</label>
                        <input type="text" id="surname" class="form-control" v-model="user.profile.surname">
                        <span class="help-block" v-if="error && errors.user.profile.surname">{{ errors.user.profile.surname }}</span>
                    </div>
                </div>
                <div class="form-group input-group-sm" v-bind:class="{ 'has-error': error && errors.user.profile.position }">
                    <label for="position">Должность</label>
                    <input type="text" id="position" class="form-control" v-model="user.profile.position">
                    <span class="help-block" v-if="error && errors.user.profile.position">{{ errors.user.profile.position }}</span>
                </div>
                <div v-if="this.$auth.user().role_id == 4 || this.$auth.user().sudo" class="form-group">
                    <label for="position">Добавить в отдел: </label>
                    <select v-model="user.department_id"> 
                        <option v-for="department in departments" v-bind:value="department.id">{{ department.title }}</option>
                    </select>
                </div>
                <div v-if="this.$auth.user().role_id == 4 || this.$auth.user().sudo" class="form-group">
                    Выбрать роль:
                    <div class="form-row" v-bind:class="{ 'has-error': error && errors.user.name }">
                        
                        <div  class="form-group col-sm-6">
                            <div class="form-sub-group">
                                <label for="one-user">Пользователь</label>
                                <input type="radio" id="one-user" value="1" v-model="user.role_id">
                            </div>
                            <div class="form-sub-group">
                                <label for="one-member">Участник</label>
                                <input type="radio" id="one-member" value="2" v-model="user.role_id">
                            </div>
                            <div class="form-sub-group">
                                <label for="one-manager">Менеджер</label>
                                <input type="radio" id="one-manager" value="3" v-model="user.role_id">
                            </div>
                            <div class="form-sub-group">
                                <label for="one-admin">Администратор</label>
                                <input type="radio" id="one-admin" value="4" v-model="user.role_id">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-sub-group">
                                <label for="sudo-user">Супер администратор</label>
                                <input type="checkbox" id="sudo-user" v-model="user.sudo">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-default">Отправить</button>
            </form>
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
                isHidden: false,
            };
        },
        mounted() {
            this.$store.dispatch('getUser', this.$route.params.id);
            this.$store.dispatch('getDepartments');
        },
        computed: {
            ...mapState({
                departments: state => state.departments.items,
                user: state => state.users.item,
                error: state => state.users.error,
                errors: state => state.users.errors,
                success: state => state.users.success,
            }),
        },
        methods: {
            update() {
                this.$store.dispatch('editUser', {
                    data: {
                        name: this.user.name,
                        surname: this.user.profile.surname,
                        position: this.user.profile.position,
                        department_id: this.user.department_id,
                        role_id: this.user.role_id,
                        sudo: this.user.sudo
                    },
                        user_id: this.$route.params.id,
                    },
                );           
            },
            deleteUser() {
                var confirmDelete = confirm("Удалить пользователя?");
                if (confirmDelete) {
                    this.$store.dispatch('deleteUser', {
                            user_id: this.$route.params.id
                        }
                    );
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