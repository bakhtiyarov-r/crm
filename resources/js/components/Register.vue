<template>
    <section>
        <div class="container">
          <div class="row">
            <div class="col-md-4 offset-4">
                <div class="title">
                  <h2>Регистрация</h2>
                </div>
                <div class="alert alert-danger" v-if="error && !success">
                    <p>Произошла ошибка, невозможно завершить регитсрацию.</p>
                </div>
                <div class="alert alert-success" v-if="success">
                    <p>Регистрация завершена. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
                </div>
                <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
                    <div class="form-group" v-bind:class="{ 'has-error': error && errors.title}">
                        <label for="orgtitle">Название компании</label>
                        <input type="text" id="orgtitle" class="form-control" v-model="title" required>
                        <span class="help-block" v-if="error && errors.title">{{ errors.title }}</span>
                    </div>
                    <div class="form-group" v-bind:class="{ 'has-error': error && errors.name }">
                        <label for="name">Имя</label>
                        <input type="text" id="name" class="form-control" v-model="name" required>
                        <span class="help-block" v-if="error && errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group" v-bind:class="{ 'has-error': error && errors.email }">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                        <span class="help-block" v-if="error && errors.email">{{ errors.email }}</span>
                    </div>
                    <div class="form-group" v-bind:class="{ 'has-error': error && errors.password }">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" class="form-control" v-model="password" required>
                        <span class="help-block" v-if="error && errors.password">{{ errors.password }}</span>
                    </div>
                    <button type="submit" class="btn btn-default">Отправить</button>
                </form>
            </div>
          </div>
        </div>
    </section>
    
</template>
<script> 
    export default {
        data(){
            return {
                title: '',
                name: '',
                email: '',
                password: '',
                error: false,
                errors: {},
                success: false
            };
        },
        methods: {
            register(){
                var app = this
                this.$auth.register({
                    data: {
                        title: app.title,
                        name: app.name,
                        email: app.email,
                        password: app.password
                    }, 
                    success: function () {
                        app.success = true
                    },
                    error: function (resp) {
                        app.error = true;
                        app.errors = resp.response.data.errors;
                    },
                    autoLogin: true,
                    rememberMe: true,
                    redirect: {name: 'tasks'},
                    fetchUser: true
                });                
            }
        }
    }
</script>