<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <v-card class="mt-2 rounded mx-auto" elevation="2" max-width="1100">
            <v-row dense class="pl-1">
                <v-col cols="3" class="bk_blue rounded-l d-none d-md-flex">
                    <v-img class="img_login rounded-sm" :src='banner.img' :lazy-src='banner.lazy'>
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </v-col>
                <v-col>
                    <div class="pb-4 mx-4">
                        <v-btn class="mr-4 mt-2" text small @click.stop="returnUsers()">
                            <v-icon left>keyboard_double_arrow_left</v-icon>
                            Regresar
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">NUEVO USUARIO</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Cree un usuario nuevo</v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Formulario -->
                            <v-form ref="form" enctype="multipart/form-data" @submit.prevent="registerUser()">
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.firstname" :rules="firstnameRules" label="Nombres *"
                                            tabindex="1" dense prepend-icon="contacts" clearable clear-icon="cancel"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.lastname" :rules="lastnameRules" label="Apellidos *"
                                            tabindex="2" dense prepend-icon="contacts" clearable clear-icon="cancel"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.email" :rules="emailRules"
                                            label="Correo electrónico *" tabindex="3" dense prepend-icon="email"
                                            clearable clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.user" tabindex="4" dense prepend-icon="person"
                                            :rules="userRules" label="Usuario *" clearable clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.password" :rules="passwordRules"
                                            label="Contraseña *" tabindex="5" dense prepend-icon="lock"
                                            :append-icon="form.show1 ? 'visibility' : 'visibility_off'"
                                            :type="form.show1 ? 'text' : 'password'"
                                            @click:append="form.show1 = !form.show1" clearable clear-icon="cancel"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.password_confirmation" :rules="passwordconfirmRules"
                                            label="Repita la contraseña *" dense tabindex="6" prepend-icon="lock"
                                            :append-icon="form.show2 ? 'visibility' : 'visibility_off'"
                                            :type="form.show2 ? 'text' : 'password'"
                                            @click:append="form.show2 = !form.show2" clearable clear-icon="cancel"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-btn class="bk_brown txt_white width_100 mb-2"
                                            @click.stop="handleFileImport()">
                                            <v-icon left>file_upload</v-icon>
                                            Subir avatar
                                        </v-btn>
                                        <v-file-input ref="uploader" v-model="form.avatar" @change="preview_img()"
                                            class="d-none" label="Haz clic(k) aquí para subir una imagen" id="avatar"
                                            prepend-icon="photo_camera" :rules="avatarRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size
                                            dense>
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/users/blank.png'">
                                            <v-btn class="bk_brown txt_white width_100" @click.stop="clean_img()">
                                                <v-icon left>delete</v-icon>
                                                Borrar avatar
                                            </v-btn>
                                        </template>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-img class="mt-0 mx-auto" :src="prev_img.url_img"
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
                                    form.firstname != '' &&
                                    form.lastname != '' &&
                                    form.email != '' &&
                                    form.user != '' &&
                                    form.password != '' &&
                                    form.password_confirmation != ''
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
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "NewUser",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-new_user.jpg",
            lazy: "/img/lazy/banner-new_user.jpg",
        },
        overlay: false,
        form: {
            firstname: "",
            lastname: "",
            user: "",
            email: "",
            password: "",
            password_confirmation: "",
            avatar: null,
            show1: false,
            show2: false,
        },
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
        passwordRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
            v => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/.test(v) || ' La contraseña debe contener al menos una Mayúscula, un número y minúsculas',
        ],
        passwordconfirmRules: [
            v => !!v || 'El repetir la contraseña es requerido',
        ],
        avatarRules: [
            v => (!v || v.size <= 25000000) || 'La imagen debe ser menor a 25MB',
        ],
        prev_img: {
            url_img: "/img/users/blank.png",
            lazy_img: "/img/lazy_users/blank.png",
            height: 200,
            width: 300,
        }
    }),
    methods: {
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        returnUsers() {
            this.overlay = true;
            this.$router.push({ name: "users" });
        },
        async registerUser() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de crear el usuario?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('firstname', this.form.firstname);
                            data.append('lastname', this.form.lastname);
                            data.append('user', this.form.user);
                            data.append('email', this.form.email);
                            data.append('password', this.form.password);
                            data.append('password_confirmation', this.form.password_confirmation);
                            this.form.avatar = document.querySelector('#avatar').files[0];
                            if (this.form.avatar) {
                                data.append('avatar', this.form.avatar);
                            }
                            this.axios.post('/api/user', data, {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                },
                            })
                                .then(response => {
                                    let title = "Error";
                                    let icon = "error";
                                    if (response.data.complete) {
                                        title = "Éxito"
                                        icon = "success";
                                    }
                                    this.$swal({
                                        title: title,
                                        icon: icon,
                                        text: response.data.message,
                                    }).then(() => {
                                        if (response.data.complete) {
                                            this.$router.push({ name: "users" });
                                        }
                                        else this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.$swal({
                                        title: "Error",
                                        icon: "error",
                                        text: "Ha ocurrido un error en la aplicación",
                                    }).then(() => {
                                        console.log(error);
                                        this.overlay = false;
                                    });
                                })
                        }
                    });
            }
            else {
                this.overlay = false;
            }
        },
        preview_img() {
            this.prev_img.url_img = URL.createObjectURL(this.form.avatar);
            this.prev_img.lazy_img = URL.createObjectURL(this.form.avatar);
        },
        clean_img() {
            this.prev_img.url_img = "/img/users/blank.png";
            this.prev_img.lazy_img = "/img/lazy_users/blank.png";
            this.form.avatar = null;
        }
    },
}
</script>