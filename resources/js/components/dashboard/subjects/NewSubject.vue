<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-card class="mt-4 rounded mx-auto" elevation="2" max-width="700">
                <v-row dense class="pl-1">
                    <v-col cols="3" class="bk_blue rounded-l d-none d-md-flex">
                        <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </v-col>
                    <v-col>
                        <div class="pb-4 mx-4">
                            <v-btn class="mr-4" text small @click.prevent="returnSubjects">
                                <v-icon left>keyboard_double_arrow_left</v-icon>
                                Regresar
                            </v-btn>
                            <v-card-title class="text-h5">
                                <p class="mx-auto">NUEVO CURSO</p>
                            </v-card-title>
                            <v-card-subtitle class="text-center">Cree un curso nuevo</v-card-subtitle>
                            <div class="px-2 pb-2">
                                <!-- Formulario -->
                                <v-form ref="form" @submit.prevent="registerSubject" lazy-validation>
                                    <small class="font-italic txt_red">Obligatorio *</small>
                                    <v-row class="mt-2">
                                        <v-col cols="12">
                                            <v-text-field v-model="form.name" :rules="nameRules" label="Titulo *"
                                                tabindex="1" dense prepend-icon="collections_bookmark" required>
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                    <template v-if="form.name != ''">
                                        <v-btn class="txt_white bk_green width_100 mt-2" type="submit">
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                    <template v-else>
                                        <v-btn class="width_100 mt-2" disabled>
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
        </div>
    </v-main>
</template>

<script>
export default {
    name: "NewSubject",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-new_subject.jpg",
            lazy: "/img/lazy/banner-new_subject.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            name: "",
        },
        nameRules: [
            v => !!v || 'El titulo del curso es requerido',
            v => (v && v.length <= 100) || 'El titulo del curso debe tener menos de 100 carácteres',
        ],
    }),
    methods: {
        returnSubjects() {
            if (this.$route.params.backnew) this.$router.push({ name: "newTopic" });
            else if (this.$route.params.backedit) this.$router.push({ name: "editTopic", params: { slug: this.$route.params.backedit } });
            else this.$router.push({ name: "subjects" });
        },
        async registerSubject() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de crear el curso?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('name', this.form.name);
                            this.axios.post('/api/subject', data)
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
                                    }).then(() => {
                                        if (response.data.complete) {
                                            if (this.$route.params.backnew) this.$router.push({ name: "newTopic" });
                                            else if (this.$route.params.backedit) this.$router.push({ name: "editTopic", params: { slug: this.$route.params.backedit } });
                                            else this.$router.push({ name: "subjects" });
                                            this.overlay = false;
                                        }
                                        else this.overlay = false;
                                    });
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
}
</script>