<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-card class="mt-4 mx-auto rounded" elevation="3" max-width="1100">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>Perfil de
                        <template v-if="user.firstname || user.lastname">
                            <template v-if="user.firstname">
                                {{ user.firstname }}
                            </template>
                            <template v-if="user.lastname">
                                {{ user.lastname }}
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
                            manage_accounts
                        </v-icon>
                        Información
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            lock
                        </v-icon>
                        Contraseña
                    </v-tab>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Su información almacenada en el sistema
                            </v-card-subtitle>
                            <!-- Formulario de ingreso -->
                            <v-form ref="form_information" enctype="multipart/form-data" lazy-validation>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="form.firstname" :rules="firstnameRules" label="Nombres"
                                            tabindex="1" required>
                                        </v-text-field>
                                        <v-text-field v-model="form.email" :rules="emailRules"
                                            label="Correo electrónico" tabindex="3" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field v-model="form.lastname" :rules="lastnameRules" label="Apellidos"
                                            tabindex="2" required>
                                        </v-text-field>
                                        <v-text-field v-model="form.user" tabindex="4" :rules="userRules"
                                            label="Usuario" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-file-input v-model="form.avatar" @change="preview_img"
                                            label="Haz clic(k) aquí para subir una imagen" id="avatar"
                                            prepend-icon="photo_camera" :rules="avatarRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size
                                            tabindex="5">
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/users/blank.png'">
                                            <v-btn class="bk_brown txt_white width_100" @click="clean_img">Borrar
                                                avatar
                                            </v-btn>
                                        </template>
                                        <v-img class="mt-4 mx-auto" :src="prev_img.url_img" :lazy-src='prev_img.url_img'
                                            :max-height="prev_img.height" :max-width="prev_img.width" contain>
                                            <template v-slot:placeholder>
                                                <v-row class="fill-height ma-0" align="center" justify="center">
                                                    <v-progress-circular indeterminate color="grey lighten-5">
                                                    </v-progress-circular>
                                                </v-row>
                                            </template>
                                        </v-img>
                                    </v-col>
                                </v-row>
                            </v-form>
                            <v-btn class="txt_white bk_green width_100 mt-4" @click.prevent="editUser">
                                Guardar
                            </v-btn>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Cambie su contraseña
                            </v-card-subtitle>
                            <!-- Formulario de ingreso -->
                            <v-form ref="form_password" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-text-field v-model="form.password" type="password" :rules="passwordRules"
                                    label="Contraseña *" tabindex="1" required>
                                </v-text-field>
                                <v-text-field v-model="form.password_confirmation" type="password"
                                    :rules="passwordconfirmRules" label="Repita la contraseña *" tabindex="2" required>
                                </v-text-field>
                            </v-form>
                            <v-btn class="txt_white bk_green width_100" @click.prevent="editPassword">
                                Guardar
                            </v-btn>
                        </div>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "Profile",
    data: () => ({
        banner: {
            img: "/img/banner/banner-new_user.jpg",
            lazy: "/img/lazy/banner-new_user.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            id: "",
            firstname: "",
            lastname: "",
            user: "",
            email: "",
            password: "",
            password_confirmation: "",
            avatar: null,
            avatar_new: 0,
        },
        firstnameRules: [
            v => !!v || 'Los nombres son requeridos',
            v => (v && v.length <= 250) || 'Los nombres deben tener menos de 250 carácteres',
        ],
        lastnameRules: [
            v => !!v || 'Los apellidos son requeridos',
            v => (v && v.length <= 250) || 'Los apellidos deben tener menos de 250 carácteres',
        ],
        emailRules: [
            v => !!v || 'El correo electrónico es requerido',
            v => (v && v.length <= 250) || 'El correo electrónico debe tener menos de 250 carácteres',
            v => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser valido',
        ],
        userRules: [
            v => !!v || 'El usuario es requerido',
            v => (v && v.length <= 50) || 'El usuario debe tener menos de 50 carácteres',
        ],
        avatarRules: [
            v => (!v || v.size <= 25000000) || 'La imagen debe ser menor a 25MB',
        ],
        passwordRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ],
        passwordconfirmRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ],
        prev_img: {
            url_img: "/img/users/blank.png",
            height: 200,
            width: 300,
        },
        user: [],
    }),
    methods: {
        async showUser() {
            this.overlay = true;
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                    this.form.firstname = this.user.firstname;
                    this.form.lastname = this.user.lastname;
                    this.form.user = this.user.user;
                    this.form.email = this.user.email;
                    if (this.user.avatar) this.prev_img.url_img = "/img/users/" + this.user.avatar;
                    this.overlay = false;
                }).catch((error) => {
                    console.log(error);
                    this.overlay = false;
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                        });

                });
        },
        async editUser() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de actualizar su información?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            //Mostramos los datos asi por la imagen
                            let data = new FormData();
                            data.append('firstname', this.form.firstname);
                            data.append('lastname', this.form.lastname);
                            data.append('user', this.form.user);
                            data.append('email', this.form.email);
                            this.form.avatar = document.querySelector('#avatar').files[0];
                            if (this.form.avatar) {
                                data.append('avatar', this.form.avatar);
                            }
                            data.append('avatar_new', this.form.avatar_new);
                            data.append('_method', "put");
                            this.axios.post('/api/profile/' + this.user.id, data, {
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
                                            window.location.href = "/dashboard/profile"
                                            this.overlay = false;
                                        }
                                        else this.overlay = false;
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
                    title: '¿Esta seguro de actualizar su contraseña?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            //Mostramos los datos asi por la imagen
                            let data = new FormData();
                            data.append('password', this.form.password);
                            data.append('password_confirmation', this.form.password_confirmation);
                            data.append('_method', "put");
                            this.axios.post('/api/profile/password/' + this.user.id, data)
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
                                            window.location.href = "/dashboard/profile"
                                            this.overlay = false;
                                        }
                                        else this.overlay = false;
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
        preview_img() {
            this.form.avatar_new = 1;
            this.prev_img.url_img = URL.createObjectURL(this.form.avatar);
        },
        clean_img() {
            this.prev_img.url_img = "/img/users/blank.png";
            this.form.avatar = null;
            this.form.avatar_new = 1;
        }
    },
    mounted() {
        this.showUser()
    },
}
</script>