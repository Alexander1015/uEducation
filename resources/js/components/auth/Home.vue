<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <v-card class="mx-auto rounded mt-2" elevation="2" max-width="700">
            <v-row dense class="pl-1">
                <v-col cols="4" class="bk_blue rounded-l d-none d-sm-flex">
                    <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </v-col>
                <v-col>
                    <div class="py-6 mx-4">
                        <v-card-title class="text-h5">
                            <p class="mx-auto">INICIAR SESIÓN</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Bienvenido a uEducation</v-card-subtitle>
                        <!-- Formulario de ingreso -->
                        <v-form ref="form" @submit.prevent="loginUser" lazy-validation>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field v-model="form.user" :rules="userRules" label="Usuario"
                                        prepend-icon="person" tabindex="1" clearable clear-icon="cancel" dense required>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="form.password" :rules="passwordRules"
                                        :append-icon="form.show ? 'visibility' : 'visibility_off'"
                                        :type="form.show ? 'text' : 'password'" @click:append="form.show = !form.show"
                                        label="Contraseña" prepend-icon="lock" tabindex="2" clearable
                                        clear-icon="cancel" dense required>
                                    </v-text-field>
                                </v-col>
                            </v-row>
                            <v-btn class="mt-10 bk_brown txt_white width_100" type="submit">
                                <v-icon left>login</v-icon>
                                Ingresar
                            </v-btn>
                        </v-form>
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "HomeAuth",
    data: () => ({
        banner: {
            img: "/img/banner/login.jpg",
            lazy: "/img/banner/login_lazy.jpg",
        },
        overlay: false,
        form: {
            user: "",
            password: "",
            show: false,
        },
        userRules: [
            v => !!v || 'El usuario es requerido'
        ],
        passwordRules: [
            v => !!v || 'La contraseña es requerida'
        ]
    }),
    mounted() {
        this.login();
    },
    methods: {
        async login() {
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                }).catch((error) => {
                    console.log(error);
                });
        },
        async loginUser() {
            if (this.$refs.form.validate()) {
                this.overlay = true;
                let data = new FormData();
                data.append('user', this.form.user);
                data.append('password', this.form.password);
                await axios.post('/api/auth', data)
                    .then(response => {
                        if (response.data.complete) {
                            this.$router.push({ name: "public", params: { session: true } });
                        }
                        else {
                            this.$swal({
                                title: "Error",
                                icon: "error",
                                text: response.data.message,
                            }).then(() => {
                                this.overlay = false;
                            });
                        }
                    }).catch((error) => {
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
        },
    },
}
</script>