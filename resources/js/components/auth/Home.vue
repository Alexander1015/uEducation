<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <v-card class="mx-auto rounded mt-4" elevation="3" width="700">
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
                            <p class="mx-auto">Inicio de Sesión</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Bienvenido a uEducation</v-card-subtitle>
                        <!-- Formulario de ingreso -->
                        <v-form ref="form" @submit.prevent="loginUser" lazy-validation>
                            <v-text-field v-model="form.user" :rules="userRules" label="Usuario" hide-details="auto"
                                tabindex="1" required>
                            </v-text-field>
                            <v-text-field v-model="form.password" type="password" :rules="passwordRules"
                                label="Contraseña" hide-details="auto" tabindex="2" required>
                            </v-text-field>
                            <v-btn class="mt-10 bk_brown txt_white width_100" type="submit">
                                Ingresar</v-btn>
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
            img: "/img/banner/banner-login.jpg",
            lazy: "/img/lazy/banner-login.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            user: "",
            password: "",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
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
                            this.$refs.form.reset();
                            this.overlay = false;
                            window.location.href = "/"
                        }
                        else {
                            this.sweet.title = "Error"
                            this.sweet.icon = "error";
                            this.$swal({
                                title: this.sweet.title,
                                icon: this.sweet.icon,
                                text: response.data.message,
                            });
                            this.overlay = false;
                        }
                    }).catch((error) => {
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
            else {
                this.overlay = false;
            }
        },
    },
}
</script>