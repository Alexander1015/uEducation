<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mt-2">
            <v-btn class="ml-4" text small @click.prevent="returnUsers">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="new_btn mr-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" fab small @click.prevent="login(), showUser()" elevation="3"
                            class="bk_blue txt_white mr-4">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Recargar</span>
                </v-tooltip>
            </div>
            <v-card class="mt-2 mx-auto" elevation="2" max-width="1100">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>
                        <template v-if="user.firstname || user.lastname">
                            <template v-if="user.firstname">
                                {{ user.firstname.toUpperCase() }}
                            </template>
                            <template v-if="user.lastname">
                                {{ user.lastname.toUpperCase() }}
                            </template>
                        </template>
                        <template v-else>
                            <v-icon>remove</v-icon>
                        </template>
                    </v-toolbar-title>
                </v-toolbar>
                <v-tabs grow>
                    <!-- Menú grow -->
                    <v-tab>
                        <v-icon left>
                            badge
                        </v-icon>
                        Información
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            lock
                        </v-icon>
                        Contraseña
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            manage_accounts
                        </v-icon>
                        Otros
                    </v-tab>
                    <!-- Información del usuario -->
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Información almacenada del usuario seleccionado
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_information" enctype="multipart/form-data" @submit.prevent="editUser"
                                lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.firstname" :rules="info.firstnameRules"
                                            label="Nombres *" tabindex="1" dense prepend-icon="contacts" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.lastname" :rules="info.lastnameRules"
                                            label="Apellidos *" tabindex="2" dense prepend-icon="contacts" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.email" :rules="info.emailRules"
                                            label="Correo electrónico *" tabindex="3" dense prepend-icon="email"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.user" tabindex="4"
                                            :rules="info.userRules" label="Usuario *" dense prepend-icon="person"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-file-input v-model="form_information.avatar" @change="preview_img"
                                            label="Haz clic(k) aquí para subir una imagen" id="avatar"
                                            prepend-icon="photo_camera" :rules="info.avatarRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size
                                            tabindex="5">
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/users/blank.png'">
                                            <v-btn class="bk_brown txt_white width_100" @click="clean_img">
                                                Reiniciar avatar
                                            </v-btn>
                                        </template>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-img class="mt-4 mx-auto" :src="prev_img.url_img"
                                            :lazy-src='prev_img.lazy_img' :max-height="prev_img.height"
                                            :max-width="prev_img.width" contain>
                                            <template v-slot:placeholder>
                                                <v-row class="fill-height ma-0" align="center" justify="center">
                                                    <v-progress-circular indeterminate color="grey lighten-5">
                                                    </v-progress-circular>
                                                </v-row>
                                            </template>
                                        </v-img>
                                    </v-col>
                                </v-row>
                                <template v-if="
                                    form_information.firstname != user.firstname ||
                                    form_information.lastname != user.lastname ||
                                    form_information.email != user.email ||
                                    form_information.user != user.user ||
                                    form_information.avatar != null ||
                                    form_information.avatar_new != 0
                                ">
                                    <v-btn class="txt_white bk_green width_100 mt-4" type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn class="width_100 mt-4" disabled>
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                            </v-form>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Cambie la contraseña del usuario seleccionado
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_password" @submit.prevent="editPassword" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form_password.password" :rules="passw.passwordRules"
                                            label="Contraseña *" tabindex="1" dense prepend-icon="lock"
                                            :append-icon="form_password.show1 ? 'visibility' : 'visibility_off'"
                                            :type="form_password.show1 ? 'text' : 'password'"
                                            @click:append="form_password.show1 = !form_password.show1" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="form_password.password_confirmation"
                                            :rules="passw.passwordconfirmRules" label="Repita la contraseña *"
                                            tabindex="2" dense prepend-icon="lock"
                                            :append-icon="form_password.show2 ? 'visibility' : 'visibility_off'"
                                            :type="form_password.show2 ? 'text' : 'password'"
                                            @click:append="form_password.show2 = !form_password.show2" required>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <template v-if="
                                    form_password.password != '' &&
                                    form_password.password_confirmation != ''
                                ">
                                    <v-btn class="txt_white bk_green width_100 mt-2" type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn class="width_100 mt-2" disabled>
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                            </v-form>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Cambie el estado del usuario en el sistema (Si esta desactivado no tendra
                                    permitido ingresar al apartado de administradores o manipular la información de
                                    la base de datos)
                                </v-card-subtitle>
                                <v-form ref="form_status" @submit.prevent="statusUser" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estado" :rules="statusRules" dense prepend-icon="rule"></v-select>
                                    <template
                                        v-if="form_status.status != (user.status == 1 ? 'Activo' : 'Desactivado')">
                                        <v-btn class="txt_white bk_green width_100" type="submit">
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                    <template v-else>
                                        <v-btn class="width_100" disabled>
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                </v-form>
                            </div>
                            <v-divider class="mt-8 mb-4"></v-divider>
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Elimine el usuario seleccionado de la base de datos, esta opcion no se puede
                                    revertir
                                </v-card-subtitle>
                                <v-btn class="txt_white bk_red width_100" @click.prevent="deleteUser">
                                    <v-icon left>delete</v-icon>
                                    Eliminar usuario
                                </v-btn>
                            </div>
                        </div>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "EditUser",
    data: () => ({
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        items_status: ["Activo", "Desactivado"],
        form_information: {
            firstname: "",
            lastname: "",
            user: "",
            email: "",
            avatar: null,
            avatar_new: 0,
        },
        form_password: {
            password: "",
            password_confirmation: "",
            show1: false,
            show2: false,
        },
        form_status: {
            status: "",
        },
        prev_img: {
            url_img: "/img/users/blank.png",
            lazy_img: "/img/lazy_users/blank.png",
            height: 200,
            width: 300,
        },
        user: {},
        login_user: {
            id: "",
        },
        info: {
            firstnameRules: [
                v => !!v || 'Los nombres son requeridos',
                v => (v && v.length <= 50) || 'Los nombres deben tener menos de 50 carácteres',
            ],
            lastnameRules: [
                v => !!v || 'Los apellidos son requeridos',
                v => (v && v.length <= 50) || 'Los apellidos deben tener menos de 50 carácteres',
            ],
            emailRules: [
                v => !!v || 'El correo electrónico es requerido',
                v => (v && v.length <= 100) || 'El correo electrónico debe tener menos de 100 carácteres',
                v => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser valido',
            ],
            userRules: [
                v => !!v || 'El usuario es requerido',
                v => (v && v.length <= 50) || 'El usuario debe tener menos de 50 carácteres',
            ],
            avatarRules: [
                v => (!v || v.size <= 25000000) || 'La imagen debe ser menor a 25MB',
            ],
        },
        passw: {
            passwordRules: [
                v => !!v || 'La contraseña es requerida',
                v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
                v => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/.test(v) || ' La contraseña debe contener al menos una Mayúscula, un número y minúsculas',
            ],
            passwordconfirmRules: [
                v => !!v || 'El repetir la contraseña es requerido',
            ],
        },
        statusRules: [
            v => !!v || 'Debe elegir un item',
        ],
    }),
    mounted() {
        this.login();
        this.showUser();
    },
    methods: {
        returnUsers() {
            this.$router.push({ name: "users" });
        },
        async login() {
            await this.axios.get('/api/auth')
                .then(response => {
                    this.login_user = response.data;
                }).catch((error) => {
                    console.log(error);
                });
        },
        async showUser() {
            this.overlay = true;
            if (this.$route.params.slug && this.login_user.slug != this.$route.params.slug) {
                await this.axios.get('/api/user/' + this.$route.params.slug)
                    .then(response => {
                        this.user = response.data;
                        if (!this.user.user) {
                            this.overlay = false;
                            this.$router.push({ name: "users" });
                        }
                        else {
                            this.form_information.firstname = this.user.firstname;
                            this.form_information.lastname = this.user.lastname;
                            this.form_information.user = this.user.user;
                            this.form_information.email = this.user.email;
                            if (this.user.avatar) {
                                this.prev_img.url_img = "/img/users/" + this.user.avatar;
                                this.prev_img.lazy_img = "/img/lazy_users/" + this.user.avatar;
                            }
                            this.form_information.avatar = null;
                            this.form_information.avatar_new = 0;
                            this.form_password.password = "";
                            this.form_password.password_confirmation = "";
                            if (this.user.status == 0) this.form_status.status = "Desactivado";
                            else if (this.user.status == 1) this.form_status.status = "Activo";
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
            }
            else {
                this.overlay = false;
                this.$router.push({ name: "users" });
            }
        },
        async editUser() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar la información del usuario?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('firstname', this.form_information.firstname);
                            data.append('lastname', this.form_information.lastname);
                            data.append('user', this.form_information.user);
                            data.append('email', this.form_information.email);
                            this.form_information.avatar = document.querySelector('#avatar').files[0];
                            if (this.form_information.avatar) {
                                data.append('avatar', this.form_information.avatar);
                            }
                            data.append('avatar_new', this.form_information.avatar_new);
                            data.append('_method', "put");
                            this.axios.post('/api/user/' + this.$route.params.slug, data, {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                },
                            })
                                .then(response => {
                                    if (response.data.complete) {
                                        this.sweet.title = "Éxito"
                                        this.sweet.icon = "success";
                                    }
                                    else {
                                        this.sweet.title = "Error"
                                        this.sweet.icon = "error";
                                    }
                                    this.$swal({
                                        title: this.sweet.title,
                                        icon: this.sweet.icon,
                                        text: response.data.message,
                                    }).then(() => {
                                        if (response.data.complete) {
                                            this.login();
                                            this.showUser();
                                        }
                                        this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                    this.$swal({
                                        title: this.sweet.title,
                                        icon: this.sweet.icon,
                                        text: error,
                                    });
                                    this.overlay = false;
                                })
                        }
                    });
            }
            else {
                this.overlay = false;
            }
        },
        async editPassword() {
            if (this.$refs.form_password.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de cambiar la contraseña?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('password', this.form_password.password);
                            data.append('password_confirmation', this.form_password.password_confirmation);
                            data.append('_method', "put");
                            this.axios.post('/api/user/password/' + this.$route.params.slug, data)
                                .then(response => {
                                    if (response.data.complete) {
                                        this.sweet.title = "Éxito"
                                        this.sweet.icon = "success";
                                    }
                                    else {
                                        this.sweet.title = "Error"
                                        this.sweet.icon = "error";
                                    }
                                    this.$swal({
                                        title: this.sweet.title,
                                        icon: this.sweet.icon,
                                        text: response.data.message,
                                    }).then(() => {
                                        if (response.data.complete) {
                                            this.login();
                                            this.showUser();
                                        }
                                        this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                    this.$swal({
                                        title: this.sweet.title,
                                        icon: this.sweet.icon,
                                        text: error,
                                    });
                                    this.overlay = false;
                                })
                        }
                    });
            }
            else {
                this.overlay = false;
            }
        },
        async statusUser() {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado del usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        let type = 3;
                        if (this.form_status.status == "Activo") type = 1;
                        else if (this.form_status.status == "Desactivado") type = 0;
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/user/status/' + this.$route.params.slug, data)
                            .then(response => {
                                if (response.data.complete) {
                                    this.sweet.title = "Éxito"
                                    this.sweet.icon = "success";
                                }
                                else {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                }
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: response.data.message,
                                }).then(() => {
                                    if (response.data.complete) {
                                        this.login();
                                        this.showUser();
                                    }
                                    this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.overlay = false;
                            });
                    }
                });
        },
        async deleteUser() {
            await this.$swal({
                title: '¿Esta seguro de eliminar el usuario?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/user/' + this.$route.params.slug)
                            .then(response => {
                                if (response.data.complete) {
                                    this.sweet.title = "Éxito"
                                    this.sweet.icon = "success";
                                }
                                else {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                }
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: response.data.message,
                                }).then(() => {
                                    if (response.data.complete) {
                                        this.overlay = false;
                                        this.$router.push({ name: "users" });
                                    }
                                    else this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.overlay = false;
                            });
                    }
                });
        },
        preview_img() {
            this.form_information.avatar_new = 1;
            this.prev_img.url_img = URL.createObjectURL(this.form_information.avatar);
            this.prev_img.lazy_img = URL.createObjectURL(this.form_information.avatar);
        },
        clean_img() {
            this.prev_img.url_img = "/img/users/blank.png";
            this.prev_img.lazy_img = "/img/lazy_users/blank.png";
            this.form_information.avatar = null;
            this.form_information.avatar_new = 1;
        }
    },
}
</script>