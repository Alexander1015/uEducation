<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <v-card class="mt-2 mx-auto rounded" elevation="2" max-width="1100">
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
                        <v-form ref="form_information" enctype="multipart/form-data" @submit.prevent="editUser()"
                            lazy-validation>
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
                                <template v-if="user.type == '0' || user.type == '1'">
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.email" :rules="emailRules"
                                            label="Correo electrónico *" tabindex="3" dense prepend-icon="email"
                                            clearable clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                </template>
                                <template v-else>
                                    <v-col cols="12" sm="12">
                                        <v-text-field v-model="form.email" :rules="emailRules"
                                            label="Correo electrónico *" tabindex="3" dense prepend-icon="email"
                                            clearable clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                </template>
                                <template v-if="user.type == '0' || user.type == '1'">
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form.user" tabindex="4" :rules="userRules" clearable
                                            clear-icon="cancel" label="Usuario *" dense prepend-icon="person" required>
                                        </v-text-field>
                                    </v-col>
                                </template>
                                <v-col cols="12" sm="12" md="6">
                                    <v-btn class="bk_brown txt_white mb-2" block @click.stop="handleFileImport()">
                                        <v-icon left>file_upload</v-icon>
                                        Subir avatar
                                    </v-btn>
                                    <v-file-input ref="uploader" v-model="form.avatar" @change="preview_img"
                                        label="Haz clic(k) aquí para subir una imágen" id="avatar" class="d-none"
                                        prepend-icon="photo_camera" :rules="avatarRules"
                                        accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size dense>
                                    </v-file-input>
                                    <template v-if="prev_img.url_img != '/img/users/blank.png'">
                                        <v-btn class="bk_brown txt_white" block @click.stop="clean_img()">
                                            <v-icon left>delete</v-icon>
                                            Borrar avatar
                                        </v-btn>
                                    </template>
                                </v-col>
                                <v-col cols="12" sm="12" md="6">
                                    <v-img class="mx-auto" :src="prev_img.url_img" :lazy-src='prev_img.url_img'
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
                            <template v-if="
                                form.firstname != user.firstname ||
                                form.lastname != user.lastname ||
                                form.email != user.email ||
                                ((user.type == '0' || user.type == '1') && form.user != user.user) ||
                                form.avatar != null ||
                                form.avatar_new != 0
                            ">
                                <v-btn class="txt_white bk_green mt-4" block type="submit">
                                    <v-icon left>save</v-icon>
                                    Guardar
                                </v-btn>
                            </template>
                            <template v-else>
                                <v-btn class="mt-4" block disabled>
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
                            Cambie su contraseña
                        </v-card-subtitle>
                        <!-- Formulario de ingreso -->
                        <v-form ref="form_password" @submit.prevent="editPassword()" lazy-validation>
                            <small class="font-italic txt_red">Obligatorio *</small>
                            <v-row class="mt-2">
                                <v-col cols="12">
                                    <v-text-field v-model="form_password.password" type="password"
                                        :rules="passwordRules" label="Contraseña *" tabindex="1" prepend-icon="lock"
                                        dense :append-icon="form_password.show1 ? 'visibility' : 'visibility_off'"
                                        :type="form_password.show1 ? 'text' : 'password'" clearable clear-icon="cancel"
                                        @click:append="form_password.show1 = !form_password.show1" required>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="form_password.password_confirmation" type="password"
                                        :rules="passwordconfirmRules" label="Repita la contraseña *" tabindex="2"
                                        prepend-icon="lock" dense
                                        :append-icon="form_password.show2 ? 'visibility' : 'visibility_off'"
                                        :type="form_password.show2 ? 'text' : 'password'" clearable clear-icon="cancel"
                                        @click:append="form_password.show2 = !form_password.show2" required>
                                    </v-text-field>
                                </v-col>
                            </v-row>
                            <template v-if="
                                form_password.password != '' &&
                                form_password.password_confirmation != ''
                            ">
                                <v-btn class="txt_white bk_green mt-4" block type="submit">
                                    <v-icon left>save</v-icon>
                                    Guardar
                                </v-btn>
                            </template>
                            <template v-else>
                                <v-btn class="mt-4" block disabled>
                                    <v-icon left>save</v-icon>
                                    Guardar
                                </v-btn>
                            </template>
                        </v-form>
                    </div>
                </v-tab-item>
            </v-tabs>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "Profile",
    data: () => ({
        overlay: false,
        form: {
            id: "",
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
            v => (!v || v.size <= 25000000) || 'La imágen debe ser menor a 25MB',
        ],
        passwordRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
            v => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/.test(v) || ' La contraseña debe contener al menos una Mayúscula, un número y minúsculas',
        ],
        passwordconfirmRules: [
            v => !!v || 'El repetir la contraseña es requerido',
        ],
        prev_img: {
            url_img: "/img/users/blank.png",
            height: 200,
            width: 300,
        },
        user: [],
    }),
    methods: {
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        async showUser() {
            this.overlay = true;
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                    this.form.firstname = this.user.firstname;
                    this.form.lastname = this.user.lastname;
                    this.form.user = this.user.user;
                    this.form.email = this.user.email;
                    if (this.user.avatar) {
                        this.prev_img.url_img = "/img/users/" + this.user.avatar + "/index.png";
                    }
                    this.overlay = false;
                }).catch((error) => {
                    console.log(error);
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                            this.$router.push({ name: "forbiden" });
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
                            //Mostramos los datos asi por la imágen
                            let data = new FormData();
                            data.append('firstname', this.form.firstname);
                            data.append('lastname', this.form.lastname);
                            if (this.user.type == '0' || this.user.type == '1') {
                                data.append('user', (this.form.user).toUpperCase());
                            }
                            data.append('email', this.form.email);
                            this.form.avatar = document.querySelector('#avatar').files[0];
                            if (this.form.avatar) {
                                data.append('avatar', this.form.avatar);
                            }
                            data.append('avatar_new', this.form.avatar_new);
                            this.axios.post('/api/profile', data, {
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
                                            window.location.href = "/dashboard/profile"
                                        }
                                        else this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.$swal({
                                        title: "Error",
                                        icon: "error",
                                        text: "Ha ocurrido un error en la aplicación",
                                    }).then(() => {
                                        this.overlay = false;
                                        console.log(error);
                                    });
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
                            //Mostramos los datos asi por la imágen
                            let data = new FormData();
                            data.append('password', this.form_password.password);
                            data.append('password_confirmation', this.form_password.password_confirmation);
                            this.axios.post('/api/profile/password', data)
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
                                            window.location.href = "/dashboard/profile"
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