<template>
    <section>
        <div class="container">
          <div class="row">
            <div class="col-md-4 offset-4">
                <div class="title">
                  <h2>Вход</h2>
                </div>
                <div class="alert alert-danger" v-if="error">
                    <p>There was an error, unable to sign in with those credentials.</p>
                </div>
                <form autocomplete="off" @submit.prevent="login" method="post">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" class="form-control" v-model="password" required>
                    </div>
                    <button type="submit" class="btn btn-default">Войти</button>
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
        email: null,
        password: null,
        error: false
      }
    },
    methods: {
      login(){
        var app = this
        this.$auth.login({
            params: {
              email: app.email,
              password: app.password
            }, 
            success: function () {},
            error: function () {},
            rememberMe: true,
            redirect: '/tasks',
            fetchUser: true,
        });       
      },
    }
  } 
</script>