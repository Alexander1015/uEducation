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
                    <div class="pb-4 mx-4">
                        <v-btn icon :to="to.auth" exact absolute>
                            <v-icon>keyboard_double_arrow_left</v-icon>
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">Registro</p>
                        </v-card-title>
                        <v-card-subtitle class="center">Cree un usuario administrador nuevo</v-card-subtitle>
                        <!-- Formulario de ingreso -->
                        <v-form ref="form" lazy-validation>
                            <v-text-field v-model="form.firstname" :rules="firstnameRules" label="Nombres" required>
                            </v-text-field>
                            <v-text-field v-model="form.lastname" :rules="lastnameRules" label="Apellidos" required>
                            </v-text-field>
                            <v-text-field v-model="form.email" :rules="emailRules" label="Correo electrónico" required>
                            </v-text-field>
                            <v-text-field v-model="form.user" :rules="userRules" label="Usuario" required>
                            </v-text-field>
                            <v-text-field v-model="form.password" type="password" :rules="passwordRules"
                                label="Contraseña" required>
                            </v-text-field>
                            <v-text-field v-model="form.password_confirmation" type="password"
                                :rules="passwordconfirmRules" label="Repita la contraseña" required>
                            </v-text-field>
                            <v-btn type="submit" class="mt-4 bk_brown txt_white width_100" @click="registerUser">Registrarme</v-btn>
                        </v-form>
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-main>
</template>

<script>

export default {
    name: "RegisterAuth",
    data: () => ({
        banner: {
            img: "/img/banner/banner-register.jpg",
            lazy: "/img/lazy/banner-register.jpg",
        },
        to: {
            auth: { name: "auth" },
        },
        overlay: false,
        snackbar: {
            active: false,
            text: "Mensaje",
            timeout: 2000,
            color: ""
        },
        form: {
            firstname: "",
            lastname: "",
            user: "",
            email: "",
            password: "",
            password_confirmation: "",
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
        passwordRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ],
        passwordconfirmRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ]
    }),
    methods: {
        async registerUser() {
            this.overlay = true;
            if (this.$refs.form.validate()) {
                await this.axios.post('/api/user', this.form)
                    .then(response => {
                        if (response.data.complete) {
                            this.snackbar.color = "green darken-1";
                            // Borramos los datos de los input
                            this.$refs.form.reset();
                        }
                        else this.snackbar.color = "red darken-1";
                        this.snackbar.text = response.data.message;
                        if (response.data.complete) {
                            this.snackbar.active = true;
                            setTimeout(() => {
                                this.overlay = false;
                                this.$router.push({ name: "auth" });
                            }, 2000);
                        }
                        else {
                            this.overlay = false;
                            this.snackbar.active = true;
                        }
                    }).catch(error => {
                        this.snackbar.color = "red darken-1"
                        this.snackbar.text = error;
                        this.overlay = false;
                        this.snackbar.active = true;
                    })
            }
            else {
                this.overlay = false;
            }
        },
    },
}
</script>