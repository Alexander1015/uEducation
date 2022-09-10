<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-2 mt-1 mb-4 mx-auto px-5 py-3">
            <div class="mb-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="allTags()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">ETIQUETAS</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_green txt_white" v-bind="attrs" v-on="on" @click.stop="gotoNew()">
                            <v-icon>add_box</v-icon>
                        </v-btn>
                    </template>
                    <span>Nuevo</span>
                </v-tooltip>
                <template v-if="user.type == '0'">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn class="ml-4 mt-n2 bk_blue txt_white" v-bind="attrs" v-on="on"
                                @click.stop="gotoLoad()">
                                <v-icon>upload_file</v-icon>
                            </v-btn>
                        </template>
                        <span>Cargar información de nuevas etiquetas</span>
                    </v-tooltip>
                </template>
            </div>
            <div class="mb-8">
                <p>Listado de las etiquetas existentes en la aplicación</p>
            </div>
            <v-text-field v-model="search" class="mb-1" prepend-icon="search" label="Buscar" tabindex="1" dense>
            </v-text-field>
            <!-- Tabla -->
            <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                showFirstLastPage: true,
                firstIcon: 'first_page',
                lastIcon: 'last_page',
                prevIcon: 'chevron_left',
                nextIcon: 'chevron_right'
            }" :loading="loading_table" loading-text="Obteniendo información"
                no-data-text="No se ha obtenido información" no-results-text="No se obtuvieron resultados" multi-sort
                :search="search" fixed-header align="center">
                <template v-slot:item.background_color="{ item }">
                    <div>
                        {{ item.background_color }}
                        <div class="show_color mx-auto" :style='"background-color:" + item.background_color + ";"'>
                        </div>
                    </div>
                </template>
                <template v-slot:item.text_color="{ item }">
                    <div>
                        {{ item.text_color }}
                        <div class="show_color mx-auto" :style='"background-color:" + item.text_color + ";"'></div>
                    </div>
                </template>
                <template v-slot:item.status="{ item }">
                    <div>
                        <template v-if="item.status == 0">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusTag(item.slug, 1)">
                                        <v-icon>
                                            check_box_outline_blank
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Habilitar</span>
                            </v-tooltip>
                        </template>
                        <template v-else-if="item.status == 1">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusTag(item.slug, 0)">
                                        <v-icon>
                                            check_box
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Deshabilitar</span>
                            </v-tooltip>
                        </template>
                        <template v-else>
                            <v-icon>indeterminate_check_box</v-icon>
                        </template>
                    </div>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon class="txt_blue" v-bind="attrs" v-on="on" @click.stop="gotoEdit(item.slug)">
                                <v-icon>
                                    settings
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Ver etiqueta</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon class="txt_red" @click.stop="deleteTag(item.slug)" v-bind="attrs" v-on="on">
                                <v-icon>
                                    delete
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Eliminar</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeTag",
    data: () => ({
        overlay: false,
        loading_table: true,
        headers: [
            { text: 'Título', value: 'name', align: 'center' },
            { text: 'Color del fondo', value: 'background_color', align: 'center' },
            { text: 'Color del texto', value: 'text_color', align: 'center' },
            { text: 'Estado', value: 'status', align: 'center' },
            { text: 'Acciones', value: 'actions', align: 'center', sortable: false },
        ],
        search: '',
        data: [],
        user: {},
    }),
    mounted() {
        this.allTags();
    },
    methods: {
        gotoEdit(item) {
            this.overlay = true;
            this.$router.push({ name: "editTag", params: { slug: item } });
        },
        gotoNew() {
            this.overlay = true;
            this.$router.push({ name: "newTag" });
        },
        gotoLoad() {
            this.overlay = true;
            this.$router.push({ name: "loadTags" });
        },
        async allTags() {
            this.overlay = true;
            this.loading_table = true;
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                }).catch((error) => {
                    console.log(error);
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                        });
                });
            this.data = [];
            await this.axios.get('/api/tag')
                .then(response => {
                    this.data = response.data;
                    this.loading_table = false;
                    this.overlay = false;
                })
                .catch(error => {
                    this.data = [];
                    this.loading_table = false;
                    this.overlay = false;
                })
        },
        async deleteTag(item) {
            await this.$swal({
                title: '¿Está seguro de eliminar la etiqueta?',
                text: "Está acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/tag/' + item)
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
                                    this.allTags()
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
                                    this.allTags()
                                    this.overlay = false;
                                });
                            });
                    }
                });
        },
        async statusTag(item, type) {
            await this.$swal({
                title: '¿Está seguro de ' + (type == 1 ? "habilitar" : (type == 0 ? "deshabilitar" : "cambiar el estado de")) + ' la etiqueta seleccionada?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/tag/status/' + item, data)
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
                                    this.allTags()
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
        }
    },
}
</script>