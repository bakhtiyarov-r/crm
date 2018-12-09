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
                        <!-- <button-orange btnClass="btn_orange task_edit" @click.native="isHidden = true" btnTitle="Править"></button-orange> -->
                    </div>
                </div>
                <div class="row content">
                    <div class="col-md-4 col-lg-4">
                        <img  :src="'/storage/avatars/' + user.profile.avatar" style="width: 100%;">
                    </div>
                    <div class="col-md-8 col-lg-8">
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
                <p>Данные изменены</p>
            </div>
            <form autocomplete="off" @submit.prevent="update" method="post">
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.name }">
                    <label for="name">Имя</label>
                    <input type="text" id="name" class="form-control" v-model="name" required>
                    <span class="help-block" v-if="error && errors.name">{{ errors.name }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.surname }">
                    <label for="surname">Фамилия</label>
                    <input type="text" id="surname" class="form-control" v-model="surname">
                    <span class="help-block" v-if="error && errors.surname">{{ errors.surname }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.position }">
                    <label for="position">Должность</label>
                    <input type="text" id="position" class="form-control" v-model="position">
                    <span class="help-block" v-if="error && errors.position">{{ errors.position }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.birthday }">
                    <label for="birthday">День рождения</label>
                    <input type="date" id="birthday" class="form-control" v-model="birthday">
                    <span class="help-block" v-if="error && errors.birthday">{{ errors.birthday }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.phone }">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" class="form-control" v-model="phone">
                    <span class="help-block" v-if="error && errors.phone">{{ errors.phone }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': error && errors.email }">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                    <span class="help-block" v-if="error && errors.email">{{ errors.email }}</span>
                </div>
                <button type="submit" class="btn btn-default">Отправить</button>
            </form>
        </div>
        <div v-show="isHidden" class="overlay">
        </div>
    </div>
</template>
<script> 
    export default {
        data(){
            return {
                user: {},
                isHidden: false,
                edit_success: false,
                error: false,
                errors: {},
                success: false
            };
        },
        mounted() {
            this.getUser();
        },
        methods: {
            getUser() {
                var app = this;
                this.axios.get('users/' + this.$route.params.id).then(response => {
                    app.user = response.data.data;
                }).catch(error => {
                    app.error = true;
                    app.errors = error.data;
                });  
            },
            update() {
                var app = this
                this.axios.post('user/edit', {
                    name: app.name,
                    surname: app.surname,
                    email: app.email,
                    phone: app.phone,
                    position: app.position,
                    birthday: app.birthday
                }).then(response => {
                    app.success = true
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