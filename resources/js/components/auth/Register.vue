<template>
    <v-main>
       <v-card class="mx-auto rounded mt-2" elevation="3" width="700">
            <v-progress-linear color="brown darken-2" :active="loading" :indeterminate="loading" absolute top>
            </v-progress-linear>
            <v-row dense class="pl-1">
                <v-col cols="4" class="bk_blue rounded-l">
                    <v-img class="img_login" :src='banner.img'>
                    </v-img>
                </v-col>
                <v-col cols="8">
                    <div class="pb-4 mx-4">
                        <v-card-title class="text-h5">
                            <p class="mx-auto">Registro</p>
                        </v-card-title>
                        <v-card-subtitle class="center">Cree un usuario administrador nuevo</v-card-subtitle>
                        <!-- Formulario de ingreso -->
                        <v-form ref="form" lazy-validation>
                            <v-text-field v-model="form.firstname" :rules="firstnameRules" :counter="200" label="Nombres" hide-details="auto"
                                required>
                            </v-text-field>
                            <v-text-field v-model="form.lastname" :rules="lastnameRules" :counter="200" label="Apellidos" hide-details="auto"
                                required>
                            </v-text-field>
                            <v-text-field v-model="form.email" :rules="emailRules" label="Correo electrónico" hide-details="auto"
                                required>
                            </v-text-field>
                            <v-text-field v-model="form.user" :rules="userRules" :counter="50" label="Usuario" hide-details="auto"
                                required>
                            </v-text-field>
                            <v-text-field v-model="form.password" :rules="passwordRules" :counter="50" label="Contraseña"
                                hide-details="auto" required>
                            </v-text-field>
                            <div class="center mt-4">
                                <v-btn class="bk_brown txt_white" @click="validate">Ingresar</v-btn>
                                <v-btn class="ml-4 bk_brown txt_white" @click="reset">Limpiar</v-btn>
                            </div>
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
            img: "./img/banner/banner-register.jpg",
        },
        form: {
            firstname: "",
            lastname: "",
            user: "",
            email: "",
            password: "",
            password_repeat: "",
        },
        errors: [],
        loading: false,
        firstnameRules: [
            v => !!v || 'Los nombres son requeridos',
            v => (v && v.length <= 200) || 'Los nombres deben tener menos de 200 carácteres',
        ],
        lastnameRules: [
            v => !!v || 'Los apellidos son requeridos',
            v => (v && v.length <= 200) || 'Los apellidos deben tener menos de 200 carácteres',
        ],
        emailRules: [
            v => !!v || 'El correo electrónico es requerido',
            v => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser valido',
        ],
        userRules: [
            v => !!v || 'El usuario es requerido',
            v => (v && v.length <= 50) || 'El usuario debe tener menos de 50 carácteres',
        ],
        passwordRules: [
            v => !!v || 'La contraseña es requerida',
            v => (v && v.length >= 8 && v.length <= 50) || 'La contraseña debe ser mayor a 8 carácteres y menor a 50 carácteres',
        ]
    }),
    methods: {
        async saveForm() {
            await axios.post('/api/registeraccount', this.form)
                .then(() => {
                    console.log('Usuario registrado');
                }).catch((error) => {
                    this.errors = error.response.data.errors;
                })
        },
       validate() {
            this.loading = true
            if (this.$refs.form.validate()) {
                return
            }
        },
        reset() {
            this.$refs.form.reset()
        },
    },
    watch: {
        loading(val) {
            if (!val) return
            setTimeout(() => (this.loading = false), 3000)
        },
    },
}
</script>