<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-snackbar v-model="snackbar.active" :color="snackbar.color" :timeout="snackbar.timeout" top elevation="24">
            {{ snackbar.text }}
            <template v-slot:action="{ attrs }">
                <v-btn class="txt_white" icon v-bind="attrs" @click="snackbar.active = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </template>
        </v-snackbar>
        <!-- Contenido -->
        <v-card class="mx-auto rounded mt-4" elevation="3" width="700">
            <v-row dense class="pl-1">
                <v-col cols="4" class="bk_blue rounded-l">
                    <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </v-col>
                <v-col cols="8">
                    <div class="py-6 mx-4">
                        <v-card-title class="text-h5">
                            <p class="mx-auto">Inicio de Sesión</p>
                        </v-card-title>
                        <v-card-subtitle class="center">Bienvenido a uEducation</v-card-subtitle>
                        <!-- Formulario de ingreso -->
                        <v-form ref="form" lazy-validation>
                            <v-text-field v-model="form.user" :rules="userRules" label="Usuario" hide-details="auto"
                                required>
                            </v-text-field>
                            <v-text-field v-model="form.password" type="password" :rules="passwordRules"
                                label="Contraseña" hide-details="auto" required>
                            </v-text-field>
                            <v-btn class="mt-4 bk_brown txt_white width_100" @click.prevent="loginUser" type="submit">
                                Ingresar</v-btn>
                        </v-form>
                        <v-divider class="mt-4"></v-divider>
                        <v-btn class="mt-4 bk_brown txt_white width_100" :to="to.register">Registrarse</v-btn>
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
            img: "/img/banner/banner-login.jpg",
            lazy: "/img/lazy/banner-login.jpg",
        },
        overlay: false,
        snackbar: {
            active: false,
            text: "Mensaje",
            timeout: 2000,
            color: ""
        },
        to: {
            register: { name: "register" },
        },
        form: {
            user: "",
            password: "",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        errors: [],
        loading: false,
        userRules: [
            v => !!v || 'El usuario es requerido'
        ],
        passwordRules: [
            v => !!v || 'La contraseña es requerida'
        ]
    }),
    methods: {
        async loginUser() {
            this.overlay = true;
            if (this.$refs.form.validate()) {
                await axios.post('/api/auth', this.form)
                    .then(response => {
                        if (response.data.complete) {
                            console.log(response.data.message);
                            this.$refs.form.reset();
                            this.overlay = false;
                            window.location.href = "/"
                        }
                        else {
                            this.snackbar.color = "red darken-1";
                            this.snackbar.text = response.data.message;
                            this.overlay = false;
                            this.snackbar.active = true;
                        }
                    }).catch((error) => {
                        this.snackbar.color = "red darken-1"
                        this.snackbar.text = error;
                        this.overlay = false;
                        this.snackbar.active = true;
                    });
            }
            else {
                this.overlay = false;
            }
        },
    },
}
</script>