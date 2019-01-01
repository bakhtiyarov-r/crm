<template>
    <div>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title">
                          <h2>{{ name }} {{ surname }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row divider">
                    <div class="col-md-9 col-lg-10">
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <button-orange btnClass="btn_orange task_edit" @click.native="isHidden = true" btnTitle="Править"></button-orange>
                    </div>
                </div>
                <div class="row content">
                    <div class="col-md-4 col-lg-4">
                        <img  :src="'/storage/avatars/' + avatar" style="width: 100%;">
                        <label for="avatarFile">Изменить фото профиля</label>
                        <div >
                            <input type="file" class="form-control-file" ref="avatar"  v-on:change="handleFilesUpload()" id="avatarFile" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Пожалуйста используйте только изображения размером не более 500KB.</small>
                            <span class="help-block" v-if="error && errors.avatar">{{ errors.avatar }}</span>
                            <button type="submit" class="btn btn-default" v-on:click="updateAvatar()">Отправить</button>
                        </div>
                        
                    </div>
                    <div class="col-md-8 col-lg-8">
                        <div>
                            <span class="task_title">Должность:</span>
                            <span class="task_value">{{position}}</span>
                        </div>
                        <div>
                            <span class="task_title">Дата приема:</span>
                            <span class="task_value">{{created_at | date}}</span>
                        </div>
                        <div>
                            <span class="task_title">День рождения:</span>
                            <span class="task_value">{{birthday | date}}</span>
                        </div>
                        <div>
                            <span class="task_title">Телефон:</span>
                            <span class="task_value">{{phone}} </span>
                        </div>
                        <div>
                            <span class="task_title">Email:</span>
                            <span class="task_value">{{email}}</span>
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
                <div class="form-row">
                    <div class="form-group input-group-sm col-sm-6" v-bind:class="{ 'has-error': error && errors.name }">
                        <label for="name">Имя</label>
                        <input type="text" id="name" class="form-control" v-model="name" required>
                        <span class="help-block" v-if="error && errors.name">{{ errors.name }}</span>
                    </div>
                    <div class="form-group input-group-sm col-sm-6" v-bind:class="{ 'has-error': error && errors.surname }">
                        <label for="surname">Фамилия</label>
                        <input type="text" id="surname" class="form-control" v-model="surname">
                        <span class="help-block" v-if="error && errors.surname">{{ errors.surname }}</span>
                    </div>
                </div>
                <div class="form-group input-group-sm" v-bind:class="{ 'has-error': error && errors.position }">
                    <label for="position">Должность</label>
                    <input type="text" id="position" class="form-control" v-model="position">
                    <span class="help-block" v-if="error && errors.position">{{ errors.position }}</span>
                </div>
                <div class="form-group input-group-sm" v-bind:class="{ 'has-error': error && errors.birthday }">
                    <label for="birthday">День рождения</label>
                    <input type="date" id="birthday" class="form-control" v-model="birthday">
                    <span class="help-block" v-if="error && errors.birthday">{{ errors.birthday }}</span>
                </div>
                <div class="form-group input-group-sm" v-bind:class="{ 'has-error': error && errors.phone }">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" class="form-control" v-model="phone">
                    <span class="help-block" v-if="error && errors.phone">{{ errors.phone }}</span>
                </div>
                <div class="form-group input-group-sm" v-bind:class="{ 'has-error': error && errors.email }">
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
                name: this.$auth.user().name,
                email: this.$auth.user().email,
                surname: this.$auth.user().profile.surname,
                phone: this.$auth.user().profile.phone,
                position: this.$auth.user().profile.position,
                birthday: this.$auth.user().profile.birthday,
                created_at: this.$auth.user().created_at,
                avatar: this.$auth.user().profile.avatar,
                uploadedAvatar: '',
                isHidden: false,
                edit_success: false,
                error: false,
                errors: {},
                success: false
            };
        },
        methods: {
            updateAvatar() {
                var app = this;
                var formData = new FormData();
                formData.append('avatar', app.uploadedAvatar);
                this.axios.post('profile/avatar', formData, {
                    headers: {
                      'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    app.avatar = response.data.data;
                    app.success = true
                }).catch(error => {
                    app.error = true;
                    app.errors = resp.response.data.errors;
                });    
            },
            handleFilesUpload() {
                this.uploadedAvatar = this.$refs.avatar.files[0];
            },
            update() {
                var app = this
                this.axios.put('profile/edit', {
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