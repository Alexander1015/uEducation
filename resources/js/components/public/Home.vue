<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="ma-2">
            <p class="text-h5 my-4 text-center">uEducation</p>
            <v-row class="mx-4">
                <v-col cols="12" sm="9">
                    <v-text-field v-model="search" class="mb-1" prepend-icon="search" label="Buscar" tabindex="1"
                        clearable clear-icon="cancel" dense>
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="3">
                    <v-select v-model="searchBy" class="mb-4" :items="sortBy" dense label="Buscar por"
                        prepend-icon="list_alt" hide-details tabindex="2" single-line @change="getData()"></v-select>
                </v-col>
            </v-row>
            <div class="mx-4">
                <template v-if="loading_table == false">
                    <v-data-iterator :items="data" :search="search" no-data-text="No se ha obtenido información"
                        no-results-text="No se obtuvieron resultados" align="center" hide-default-footer>
                        <template v-slot:default="props">
                            <v-row v-if='searchBy == "Materias"'>
                                <v-col v-for="item in props.items" :key="item.slug" class="mx-auto" cols="12" sm="6"
                                    lg="4">
                                    <v-card :to='{ name: "publicSubject", params: { subject: item.slug } }'>
                                        <v-img class="mx-auto"
                                            :src='"/img/subjects/" + (item.img ? item.img : "blank.png")'
                                            :lazy-src='"/img/lazy_subjects/" + (item.img ? item.img : "blank.png")'
                                            height="250">
                                            <template v-slot:placeholder>
                                                <v-row class="fill-height ma-0" align="center" justify="center">
                                                    <v-progress-circular indeterminate color="grey lighten-5">
                                                    </v-progress-circular>
                                                </v-row>
                                            </template>
                                        </v-img>
                                        <v-card-title class="bk_blue txt_white">
                                            <span class="subtitle-2 text-uppercase font-weight-bold mx-auto">
                                                {{ item.name }}
                                            </span>
                                        </v-card-title>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </template>
                    </v-data-iterator>
                </template>
                <!-- Cargando -->
                <template v-else>
                    <v-row>
                        <v-col cols="12" sm="6" lg="4">
                            <v-skeleton-loader v-bind="attrs" type="date-picker"></v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" lg="4">
                            <v-skeleton-loader v-bind="attrs" type="list-item-avatar-three-line, image, article">
                            </v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" lg="4">
                            <v-skeleton-loader v-bind="attrs"
                                type="list-item-avatar, divider, list-item-three-line, card-heading, image, actions">
                            </v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" lg="4">
                            <v-skeleton-loader v-bind="attrs" type="card-avatar, article, actions"></v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" lg="4">
                            <v-skeleton-loader v-bind="attrs"
                                type="table-heading, list-item-two-line, image, table-tfoot">
                            </v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" lg="4">
                            <v-skeleton-loader v-bind="attrs" type="date-picker"></v-skeleton-loader>
                        </v-col>
                    </v-row>
                </template>
            </div>
        </div>
    </v-main>
</template>

<script>
export default {
    name: 'HomePublic',
    data: () => ({
        attrs: {
            class: 'mb-6',
            elevation: 2,
            loading: true,
        },
        overlay: false,
        loading_table: true,
        search: '',
        searchBy: "Materias",
        sortBy: [
            "Materias",
            "Temas",
        ],
        data: [],
    }),
    mounted() {
        this.getData();
    },
    methods: {
        async getData() {
            this.overlay = true;
            this.loading_table = true;
            this.data = [];
            let search = new FormData();
            if (this.searchBy == "Materias") search.append('search', 0);
            else if (this.searchBy == "Temas") search.append('search', 1);
            else search.append('search', 2);
            await this.axios.post('/api/getdata', search)
                .then(response => {
                    const items = response.data;
                    if (items.complete) {
                        this.data = items.data;
                        if (items.search == 0) items.search = "Materias";
                        else items.search = "Temas";
                    }
                    else {
                        console.log(items.message);
                    }
                    this.loading_table = false;
                    this.overlay = false;
                })
                .catch(error => {
                    this.data = [];
                    this.loading_table = false;
                    this.overlay = false;
                })
        },
    }
}
</script>