<template>
    <v-main>
        <v-dialog v-model="dialog" max-width="900" persistent transition="dialog-bottom-transition">
            <!-- Overlay -->
            <v-overlay :value="overlay" :absolute="true">
                <v-progress-circular indeterminate size="64"></v-progress-circular>
            </v-overlay>
            <!-- Contenido -->
            <v-card class="pa-0 ma-0">
                <v-container>
                    <v-row>
                        <v-col cols="4" class="bk_blue d-none d-md-flex pa-0">
                            <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                                <template v-slot:placeholder>
                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                    </v-row>
                                </template>
                            </v-img>
                        </v-col>
                        <v-col>
                            <v-card-title class="text-h5">
                                <p class="mx-auto">Actualizar Contraseña de {{ user }}</p>
                            </v-card-title>
                            <v-card-subtitle class="text-center">Actualize la contraseña del usuario seleccionado
                            </v-card-subtitle>
                            <div class="px-2 pb-2">
                                <!-- Formulario de ingreso -->
                                <v-form ref="form" lazy-validation>
                                    <small class="font-italic txt_red">Obligatorio *</small>
                                    <v-text-field v-model="form.password" type="password" :rules="passwordRules"
                                        label="Contraseña *" tabindex="1" required>
                                    </v-text-field>
                                    <v-text-field v-model="form.password_confirmation" type="password"
                                        :rules="passwordconfirmRules" label="Repita la contraseña *" tabindex="2"
                                        required>
                                    </v-text-field>
                                </v-form>
                            </div>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn class="bk_red txt_white" :to='{ name: "users" }'>
                                    Cancelar
                                </v-btn>
                                <v-btn class="txt_white bk_green" @click="editPassword">
                                    Actualizar</v-btn>
                            </v-card-actions>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </v-main>
</template>

<script>
export default {
    name: "passwordUser",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-password_user.jpg",
            lazy: "/img/lazy/banner-password_user.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            password: "",
            password_confirmation: "",
        },
        passwordRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ],
        passwordconfirmRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ],
        user: "",
    }),
    methods: {
        async showUser() {
            if (this.$route.params.id) {
                await this.axios.get('/api/user/' + this.$route.params.id)
                    .then(response => {
                        const { user } = response.data;
                        if (!user) {
                            window.location.href = "/dashboard/users"
                        }
                        else {
                            this.user = user;
                        }
                    }).catch((error) => {
                        console.log(error);
                    });
            }
            else {
                window.location.href = "/dashboard/users"
            }
        },
        async editPassword() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de actualizar la contraseña?',
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
                            this.axios.post('/api/user/password/' + this.$route.params.id, data)
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
                                    });
                                    if (response.data.complete) {
                                        setTimeout(() => {
                                            this.overlay = false;
                                            window.location.href = "/dashboard/users"
                                        }, 2000);
                                    }
                                    else this.overlay = false;
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
    },
    mounted() {
        this.showUser()
    },
}
</script>