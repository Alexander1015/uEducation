<template>
    <v-main>
        <v-card class="mx-auto rounded mt-10" elevation="3" width="700">
            <v-progress-linear color="brown darken-2" :active="loading" :indeterminate="loading" absolute top>
            </v-progress-linear>
            <v-row dense class="pl-1">
                <v-col cols="4" class="bk_blue rounded-l">
                    <v-img class="img_login" :src='banner.img'>
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
                            <v-text-field v-model="form.password" :rules="passwordRules" label="Contraseña"
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
    name: "HomeAuth",
    data: () => ({
        banner: {
            img: "./img/banner/banner-login.jpg",
        },
        form: {
            user: "",
            password: "",
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
            await axios.post('/api/login', this.form)
                .then(() => {
                    this.$router.push({ name: "dashboard" });
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