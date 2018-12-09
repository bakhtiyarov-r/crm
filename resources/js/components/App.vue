<template>
  <div>
    <header>
      <div class="container header">
        <div class="row">
          <div class="col-lg-7">
            <div class="logo">
               <router-link :to="{ name: 'home' }">taskon</router-link>
            </div>
            <div class="menu">
              <router-link :to="{ name: 'company' }" v-if="$auth.check()"  class="menu_item">Компания</router-link>
              <router-link :to="{ name: 'projects' }" v-if="$auth.check()"  class="menu_item">Проекты</router-link>
              <router-link :to="{ name: 'tasks' }" v-if="$auth.check()"  class="menu_item">Задания</router-link>
              </div>  
          </div>
          <div class="col-lg-5" style="display:flex; justify-content: flex-end;">
            <div class="user">
              <div v-if="$auth.check()" class="user_notification">
                <img src="../../assets/ic-notifications-black-24-px.png">
                <div class="user_notification-full" :class="{full: $auth.user().notifications.length > 0}"></div>
                <div class="notification" v-if="$auth.user().notifications.length > 0">
                  <div class="notification__item" v-for="notification in $auth.user().notifications">
                    <div class="notification__title">
                      {{notification.data.topic}}
                    </div>
                    <div class="notification__link">
                      <a :href="notification.data.link">{{notification.data.title}}</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="user_name">
                <router-link :to="{ name: 'login' }" v-if="!$auth.check()">Войти </router-link>
                <router-link :to="{ name: 'register' }" v-if="!$auth.check()">Регистрация </router-link>
                <div v-if="$auth.check()" href="#">
                  <div class="profile" >{{$auth.user().name}}&nbsp;&nbsp;
                    <div class="profile__edit">
                      <router-link :to="{ name: 'dashboard' }"> Профиль</router-link>
                      <a href="#" @click.prevent="$auth.logout()">Выйти </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="user_help">
                <a href="#"><img src="../../assets/ic-help-outline-24-px-copy-3.png"></a>
              </div>             
            </div>  
          </div>
        </div>
      </div>
    </header>
    
    <router-view></router-view>
  </div>

</template>

<script>
  

  export default {
    // data() {
    //   return {
    //     full: false
    //   }
    // },
    // mounted() {
    //   this.notifFull();
    // },
    // methods: {
    //   notifFull() {
    //     var app = this;
    //     if (app.$auth.user()) {
    //       if (app.$auth.user().length > 0 ) {
    //         app.full = true;
    //       } else {
    //         app.full = false;
    //       }
    //     }
    //   }
    // }
  }
</script>

