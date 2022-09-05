<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mt-2">
            <v-btn class="ml-4" text small @click.stop="returnStudents()">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <v-card class="mt-2 mx-auto" elevation="2" max-width="1100">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>
                        <template v-if="user.firstname || user.lastname">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn class="mt-n1" v-bind="attrs" v-on="on" small icon @click.stop="showUser()">
                                        <v-icon>autorenew</v-icon>
                                    </v-btn>
                                </template>
                                <span>Actualizar</span>
                            </v-tooltip>
                            <span>
                                <template v-if="user.firstname">
                                    {{ user.firstname.toUpperCase() }}
                                </template>
                                <template v-if="user.lastname">
                                    {{ user.lastname.toUpperCase() }}
                                </template>
                            </span>
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
                            <v-card-subtitle class="text-justify">
                                Información almacenada del estudiante seleccionado
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_information" enctype="multipart/form-data" @submit.prevent="editUser()"
                                lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.firstname" :rules="info.firstnameRules"
                                            label="Nombres *" tabindex="1" dense prepend-icon="contacts" clearable
                                            clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.lastname" :rules="info.lastnameRules"
                                            label="Apellidos *" tabindex="2" dense prepend-icon="contacts" clearable
                                            clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.email" :rules="info.emailRules"
                                            label="Correo electrónico *" tabindex="3" dense prepend-icon="email"
                                            clearable clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.user" tabindex="4"
                                            :rules="info.userRules" label="Usuario *" dense prepend-icon="person"
                                            clearable clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-btn class="bk_brown txt_white mb-2" block @click.stop="handleFileImport()">
                                            <v-icon left>file_upload</v-icon>
                                            Subir avatar
                                        </v-btn>
                                        <v-file-input ref="uploader" v-model="form_information.avatar"
                                            @change="preview_img()" class="d-none"
                                            label="Haz clic(k) aquí para subir una imágen" id="avatar"
                                            prepend-icon="photo_camera" :rules="info.avatarRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size>
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/users/blank.png'">
                                            <v-btn class="bk_brown txt_white my-2" block @click.stop="clean_img()">
                                                <v-icon left>delete</v-icon>
                                                Borrar avatar
                                            </v-btn>
                                        </template>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-img class="mt-n2 mx-auto" :src="prev_img.url_img"
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
                            <v-card-subtitle class="text-justify">
                                Cambie la contraseña del usuario seleccionado
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_password" @submit.prevent="editPassword()" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form_password.password" :rules="passw.passwordRules"
                                            label="Contraseña *" tabindex="1" dense prepend-icon="lock"
                                            :append-icon="form_password.show1 ? 'visibility' : 'visibility_off'"
                                            :type="form_password.show1 ? 'text' : 'password'"
                                            @click:append="form_password.show1 = !form_password.show1" clearable
                                            clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="form_password.password_confirmation"
                                            :rules="passw.passwordconfirmRules" label="Repita la contraseña *"
                                            tabindex="2" dense prepend-icon="lock"
                                            :append-icon="form_password.show2 ? 'visibility' : 'visibility_off'"
                                            :type="form_password.show2 ? 'text' : 'password'"
                                            @click:append="form_password.show2 = !form_password.show2" clearable
                                            clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <template v-if="
                                    form_password.password != '' &&
                                    form_password.password_confirmation != ''
                                ">
                                    <v-btn class="txt_white bk_green mt-2" block type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn class="mt-2" block disabled>
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
                                    Cambie el estado del usuario en el sistema (Si esta deshabilitado no podrá ingresar)
                                </v-card-subtitle>
                                <v-form ref="form_status" @submit.prevent="statusUser()" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estádo" :rules="statusRules" dense prepend-icon="rule"></v-select>
                                    <template
                                        v-if="form_status.status != (user.status == 1 ? 'Habilitado' : 'Deshabilitado')">
                                        <v-btn class="txt_white bk_green" block type="submit">
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                    <template v-else>
                                        <v-btn block disabled>
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                </v-form>
                            </div>
                            <v-divider class="mt-8 mb-4"></v-divider>
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Elimine el usuario seleccionado de la base de datos, esta opción no se puede
                                    revertir
                                </v-card-subtitle>
                                <v-btn class="txt_white bk_red" block @click.stop="deleteUser()">
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
    name: "EditStudent",
    data: () => ({
        overlay: false,
        items_status: ["Habilitado", "Deshabilitado"],
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
            lazy_img: "/img/users/blank_lazy.png",
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
                v => (v && v.length <= 50) || 'Los nombres deben tener menos de 50 caracteres',
            ],
            lastnameRules: [
                v => !!v || 'Los apellidos son requeridos',
                v => (v && v.length <= 50) || 'Los apellidos deben tener menos de 50 caracteres',
            ],
            emailRules: [
                v => !!v || 'El correo electrónico es requerido',
                v => (v && v.length <= 100) || 'El correo electrónico debe tener menos de 100 caracteres',
                v => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser valido',
            ],
            userRules: [
                v => !!v || 'El usuario es requerido',
                v => (v && v.length <= 50) || 'El usuario debe tener menos de 50 caracteres',
            ],
            avatarRules: [
                v => (!v || v.size <= 25000000) || 'La imágen debe ser menor a 25MB',
            ],
        },
        passw: {
            passwordRules: [
                v => !!v || 'La contraseña es requerida',
                v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 caracteres y menor a 50 caracteres',
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
        this.showUser();
    },
    methods: {
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        returnStudents() {
            this.overlay = true;
            this.$router.push({ name: "students" });
        },
        async showUser() {
            this.overlay = true;
            if (this.$route.params.slug && this.login_user.slug != this.$route.params.slug) {
                await this.axios.get('/api/auth')
                    .then(response => {
                        this.login_user = response.data;
                        if (this.login_user.type != "0") {
                            this.$router.push({ name: "forbiden" });
                        }
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
                await this.axios.get('/api/student/' + this.$route.params.slug)
                    .then(response => {
                        this.user = response.data;
                        if (!this.user.user) {
                            this.$router.push({ name: "error" });
                        }
                        else {
                            this.form_information.firstname = this.user.firstname;
                            this.form_information.lastname = this.user.lastname;
                            this.form_information.user = this.user.user;
                            this.form_information.email = this.user.email;
                            if (this.user.avatar) {
                                this.prev_img.url_img = "/img/users/" + this.user.avatar + "/index.png";
                                this.prev_img.lazy_img = "/img/users/" + this.user.avatar + "/lazy.png";
                            }
                            this.form_information.avatar = null;
                            this.form_information.avatar_new = 0;
                            if (this.form_password.password != "" || this.form_password.password_confirmation != "") {
                                this.form_password.password = "";
                                this.form_password.password_confirmation = "";
                                this.$refs.form_password.reset();
                            }
                            else {
                                this.form_password.password = "";
                                this.form_password.password_confirmation = "";
                            }
                            if (this.user.status == 0) this.form_status.status = "Deshabilitado";
                            else if (this.user.status == 1) this.form_status.status = "Habilitado";
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.$router.push({ name: "error" });
                    });
            }
            else {
                this.$router.push({ name: "error" });
            }
        },
        async editUser() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Está seguro de modificar la información del usuario?',
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
                            data.append('user', (this.form_information.user).toUpperCase());
                            data.append('email', this.form_information.email);
                            this.form_information.avatar = document.querySelector('#avatar').files[0];
                            if (this.form_information.avatar) {
                                data.append('avatar', this.form_information.avatar);
                            }
                            data.append('avatar_new', this.form_information.avatar_new);
                            data.append('_method', "put");
                            this.axios.post('/api/student/' + this.$route.params.slug, data, {
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
                                            this.showUser();
                                        }
                                        this.overlay = false;
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
        async editPassword() {
            if (this.$refs.form_password.validate()) {
                await this.$swal({
                    title: '¿Está seguro de cambiar la contraseña?',
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
                            this.axios.post('/api/student/password/' + this.$route.params.slug, data)
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
                                            this.showUser();
                                        }
                                        this.overlay = false;
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
        async statusUser() {
            await this.$swal({
                title: '¿Está seguro de cambiar el estado del usuario?',
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
                        if (this.form_status.status == "Habilitado") type = 1;
                        else if (this.form_status.status == "Deshabilitado") type = 0;
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/student/status/' + this.$route.params.slug, data)
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
                                        this.showUser();
                                    }
                                    this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                });
                            });
                    }
                });
        },
        async deleteUser() {
            await this.$swal({
                title: '¿Está seguro de eliminar el usuario?',
                text: "Está acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/student/' + this.$route.params.slug)
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
                                        this.$router.push({ name: "students" });
                                    }
                                    else this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                });
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
            this.prev_img.lazy_img = "/img/users/blank_lazy.png";
            this.form_information.avatar = null;
            this.form_information.avatar_new = 1;
        }
    },
}
</script>