<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-2 mt-1 mb-4 mx-auto px-5 py-3">
            <v-btn class="mb-4" text small @click.stop="returnHome()">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="mb-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="showImages()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">IMÁGENES DEL CAROUSEL</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_green txt_white" v-bind="attrs" v-on="on"
                            @click.stop="handleFileImport()">
                            <v-icon>add_box</v-icon>
                        </v-btn>
                    </template>
                    <span>Agregar nueva imágen</span>
                </v-tooltip>
            </div>
            <div class="mb-8">
                <p>Listado de las imágenes/noticias mostradas en el carousel de la pantalla principal</p>
            </div>
            <div>
                <v-file-input ref="uploader" v-model="img" @change="uploadImage()"
                    label="Haz clic(k) aquí para subir una imágen" id="img" class="d-none" prepend-icon="photo_camera"
                    :rules="imgRules" accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size>
                </v-file-input>
            </div>
            <div>
                <draggable class="list-group" tag="ul" v-model="carousel" v-bind="dragOptions" @start="drag = true"
                    @end="drag = false">
                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                        <li id="carousel_drag" class="cursor my-1 mr-5 pa-2" v-for="item in carousel" :key="item.key">
                            <div class="carousel_drag_text">
                                <v-avatar size="25" class="caption bk_tags txt_white">
                                    {{ item.key }}
                                </v-avatar>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon class="txt_red ml-2" @click.stop="deleteImage(item.id)"
                                            v-bind="attrs" v-on="on">
                                            <v-icon>
                                                delete
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Eliminar</span>
                                </v-tooltip>
                            </div>
                            <v-icon class="carousel_drag_icon txt_blue">view_list</v-icon>
                            <div class="mx-auto">
                                <v-img class="mt-0 mx-auto" :src='"/img/carousel/" + item.img'
                                    :lazy-src='"/img/lazy_carousel/" + item.img' max-height="150" contain>
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate color="grey lighten-5">
                                            </v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                            </div>
                        </li>
                    </transition-group>
                </draggable>
                <template v-if="carousel != carousel_copy">
                    <v-btn class="txt_white bk_green mt-4" block @click.stop="saveCarousel()">
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
            </div>
        </div>
    </v-main>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    name: 'HomePublic',
    components: {
        draggable
    },
    data: () => ({
        overlay: false,
        img: null,
        drag: false,
        imgRules: [
            v => (!v || v.size <= 25000000) || 'La imágen debe ser menor a 25MB',
        ],
        carousel: [],
        carousel_copy: [],
    }),
    mounted() {
        this.showImages();
    },
    computed: {
        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            };
        },
    },
    methods: {
        returnHome() {
            this.overlay = true;
            this.$router.push("/");
        },
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        async uploadImage() {
            this.overlay = true;
            let data = new FormData();
            this.img = document.querySelector('#img').files[0];
            if (this.img) {
                data.append('img', this.img);
                this.axios.post('/api/carousel', data)
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
                            this.showImages();
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
            else {
                this.$swal({
                    title: "Error",
                    icon: "error",
                    text: "No se ha cargado la imágen al servidor",
                }).then(() => {
                    this.overlay = false;
                });
            }
        },
        async showImages() {
            this.overlay = true;
            await this.axios.get('/api/carousel/')
                .then(response => {
                    this.carousel = this.carousel_copy = response.data;
                    this.overlay = false;
                }).catch((error) => {
                    console.log(error);
                    this.overlay = false;
                });
        },
        async saveCarousel() {
            await this.$swal({
                title: '¿Esta seguro de cambiar lel orden de los temas seleccionados?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    this.overlay = true;
                    let data = new FormData();
                    for (let item of this.carousel) {
                        data.append('carousel[]', item.id);
                    }
                    this.axios.post('/api/carousel/list', data)
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
                                    this.showImages();
                                }
                                this.overlay = false;
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
                });
        },
        async deleteImage(item) {
            await this.$swal({
                title: '¿Esta seguro de eliminar la imágen seleccionada?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/carousel/' + item)
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
                                    this.overlay = false;
                                    this.showImages();
                                })
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                    this.showImages();
                                });
                            });
                    }
                });
        }
    },
}
</script>