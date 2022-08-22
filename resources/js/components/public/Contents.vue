<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="ma-2">
            <p class="text-h6 my-4 text-center">CONTENIDO</p>
            <v-row class="mx-4">
                <v-col cols="12" sm="9">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field v-model="search" class="mb-4" prepend-icon="search" label="Buscar"
                                tabindex="1" dense>
                            </v-text-field>
                            <template v-if='searchBy == "Temas" && data_tags.length > 0'>
                                <v-autocomplete v-model="search_tags" :items="data_tags" label="Etiquetas"
                                    prepend-icon="local_offer" dense :loading="loading" item-text="name"
                                    @change="functionTags()" hide-no-data append-icon="arrow_drop_down" tabindex="3"
                                    clearable clear-icon="cancel" chips small-chips multiple hide-selected>
                                    <template v-slot:selection="tag">
                                        <v-chip class="my-1" :color="tag.item.background_color"
                                            :style='"color:" + tag.item.text_color + ";"' v-bind="tag.attrs" close
                                            @click="tag.select" @click:close="remove(tag.item)"
                                            :input-value="tag.selected" close-icon="close">
                                            {{ tag.item.name }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="data">
                                        <v-list-item-content v-text="data.item.name">
                                        </v-list-item-content>
                                    </template>
                                </v-autocomplete>
                            </template>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12" sm="3">
                    <v-select v-model="searchBy" class="mb-4" :items="sortBy" dense label="Buscar por"
                        prepend-icon="list_alt" hide-details tabindex="2" single-line @change="getData()"></v-select>
                </v-col>
            </v-row>
            <div class="mx-4">
                <template v-if="loading == false">
                    <template v-if="totalRecords > 12">
                        <div class="d-none d-sm-flex d-md-none">
                            <v-pagination class="mx-auto mt-n2 mb-4" color="#091E32" v-model="page" :length="pageCount"
                                total-visible="8" prev-icon="chevron_left" next-icon="chevron_right">
                            </v-pagination>
                        </div>
                    </template>
                    <v-data-iterator :items="data" :search="search" :items-per-page.sync="itemsPerPage"
                        :page.sync="page" no-data-text="No se ha obtenido información"
                        no-results-text="No se obtuvieron resultados" align="center" hide-default-footer>
                        <template v-slot:default="props">
                            <v-row v-if='searchBy == "Materias"'>
                                <v-col v-for="item in props.items" :key="item.key" class="mx-auto" cols="12" sm="6"
                                    md="4" lg="3">
                                    <v-hover v-slot="{ hover }">
                                        <v-card @click.stop="goTo(data_all[item.key].slug, data_all[item.key].first)"
                                            :elevation="hover ? 10 : 2">
                                            <v-img class="mx-auto"
                                                :src='"/img/subjects/" + (data_all[item.key].img ? data_all[item.key].img + "/index.png" : "blank.png")'
                                                :lazy-src='"/img/subjects/" + (data_all[item.key].img ? data_all[item.key].img + "/lazy.png" : "blank_lazy.png")'
                                                height="175">
                                                <template v-slot:placeholder>
                                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                                        <v-progress-circular indeterminate color="grey lighten-5">
                                                        </v-progress-circular>
                                                    </v-row>
                                                </template>
                                                <v-expand-transition>
                                                    <div v-if="hover"
                                                        class="d-flex transition-fast-in-fast-out v-card-reveal display-3 txt_white bk_brown"
                                                        style="height: 100%">
                                                        <v-icon left class="txt_white">
                                                            visibility
                                                        </v-icon>
                                                        <span class="subtitle-1 font-weight-bold">
                                                            VER
                                                        </span>
                                                    </div>
                                                </v-expand-transition>
                                            </v-img>
                                            <v-card-text class="bk_blue txt_white">
                                                <span class="subtitle-2 text-uppercase font-weight-bold mx-auto">
                                                    [{{ item.code }}] {{ item.name }}
                                                </span>
                                            </v-card-text>
                                        </v-card>
                                    </v-hover>
                                </v-col>
                            </v-row>
                            <v-row v-if='searchBy == "Temas"'>
                                <v-col v-for="item in props.items" :key="item.key" class="mx-auto" cols="12" sm="6"
                                    md="4" lg="3">
                                    <v-hover v-slot="{ hover }">
                                        <v-card
                                            @click.stop="goTo(data_all[item.key].subject_slug, data_all[item.key].slug)">
                                            <v-img class="mx-auto"
                                                :src='"/img/topics/" + (data_all[item.key].img ? data_all[item.key].img + "/index.png": "blank.png")'
                                                :lazy-src='"/img/topics/" + (data_all[item.key].img ? data_all[item.key].img + "/lazy.png" : "blank.png")'
                                                height="175">
                                                <template v-slot:placeholder>
                                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                                        <v-progress-circular indeterminate color="grey lighten-5">
                                                        </v-progress-circular>
                                                    </v-row>
                                                </template>
                                                <v-expand-transition>
                                                    <div v-if="hover"
                                                        class="d-flex transition-fast-in-fast-out v-card-reveal display-3 txt_white bk_brown"
                                                        style="height: 100%">
                                                        <v-icon left class="txt_white">
                                                            visibility
                                                        </v-icon>
                                                        <span class="subtitle-1 font-weight-bold">
                                                            VER
                                                        </span>
                                                    </div>
                                                </v-expand-transition>
                                            </v-img>
                                            <v-card-text class="bk_blue txt_white">
                                                <span class="subtitle-1 text-uppercase font-weight-bold mx-auto">
                                                    {{ item.name }}
                                                </span>
                                                <br />
                                                <small class="caption text-uppercase font-weight-bold mx-auto">
                                                    <small>
                                                        [{{ item.code }}] {{ item.subject }}
                                                    </small>
                                                </small>
                                                <!-- Abstract -->
                                                <template v-if="data_all[item.key].abstract">
                                                    <v-divider class="my-2" color="white"></v-divider>
                                                    <div class="text-justify caption">
                                                        <span>
                                                            {{ data_all[item.key].abstract }}
                                                        </span>
                                                    </div>
                                                </template>
                                                <!-- Tags -->
                                                <template v-if="item.tags.length > 0">
                                                    <v-divider class="my-2" color="white"></v-divider>
                                                    <template v-for="tag in item.tags">
                                                        <v-chip class="ml-2 mr-1 mb-1" :color="tag.background_color"
                                                            :style='"color:" + tag.text_color + ";"'>
                                                            <small class="caption text-uppercase">
                                                                {{ tag.name }}
                                                            </small>
                                                        </v-chip>
                                                    </template>
                                                </template>
                                            </v-card-text>
                                        </v-card>
                                    </v-hover>
                                </v-col>
                            </v-row>
                        </template>
                    </v-data-iterator>
                    <template v-if="totalRecords > 12">
                        <!-- Pagination -->
                        <v-pagination class="mx-auto mt-6 mb-2" color="#091E32" v-model="page" :length="pageCount"
                            total-visible="8" prev-icon="chevron_left" next-icon="chevron_right">
                        </v-pagination>
                    </template>
                </template>
                <!-- Cargando -->
                <template v-else>
                    <v-row>
                        <v-col cols="12" sm="6" md="4" lg="3">
                            <v-skeleton-loader v-bind="attrs" type="date-picker"></v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="3">
                            <v-skeleton-loader v-bind="attrs" type="list-item-avatar-three-line, image, article">
                            </v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="3">
                            <v-skeleton-loader v-bind="attrs"
                                type="list-item-avatar, divider, list-item-three-line, card-heading, image, actions">
                            </v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="3">
                            <v-skeleton-loader v-bind="attrs" type="card-avatar, article, actions"></v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="3">
                            <v-skeleton-loader v-bind="attrs"
                                type="table-heading, list-item-two-line, image, table-tfoot">
                            </v-skeleton-loader>
                        </v-col>
                        <v-col cols="12" sm="6" md="4" lg="3">
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
    name: 'ContentsPublic',
    data: () => ({
        page: 1,
        itemsPerPage: 12,
        attrs: {
            class: 'mb-6',
            elevation: 2,
            loading: true,
        },
        overlay: false,
        loading: true,
        search: '',
        search_tags: [],
        searchBy: "Materias",
        sortBy: [
            "Materias",
            "Temas",
        ],
        data: [],
        data_all: [],
        data_tags: [],
    }),
    mounted() {
        this.getData(null);
    },
    computed: {
        totalRecords() {
            return this.data.length;
        },
        pageCount() {
            let $count = this.totalRecords / this.itemsPerPage;
            return $count < 1 ? 1 : $count;
        },
    },
    methods: {
        goTo(subject, topic) {
            this.overlay = true;
            this.$router.push({ name: "publicTopic", params: { subject: subject, topic: topic } });
        },
        functionTags() {
            if (this.search_tags.length > 5) this.search_tags.pop();
            else {
                this.getData(this.search_tags);
            }
        },
        remove(item) {
            const index = this.search_tags.indexOf(item.name)
            if (index >= 0) this.search_tags.splice(index, 1)
            this.getData(this.search_tags);
        },
        async getData(tags) {
            this.overlay = true;
            this.loading = true;
            this.data = [];
            this.data_all = [];
            this.data_tags = [];
            let search = new FormData();
            if (this.searchBy == "Materias") search.append('search', 0);
            else if (this.searchBy == "Temas") search.append('search', 1);
            else search.append('search', 2);
            if (tags && tags.length > 0 && this.searchBy == "Temas") {
                for (let tag of tags) {
                    search.append('tags[]', tag);
                }
            }
            await this.axios.post('/api/getdata', search)
                .then(response => {
                    const items = response.data;
                    if (items.complete) {
                        this.data_tags = items.tags
                        this.data = items.data;
                        this.data_all = items.data_all;
                        if (items.search == 0) items.search = "Materias";
                        else items.search = "Temas";
                    }
                    else {
                        console.log(items.message);
                    }
                    this.loading = false;
                    this.overlay = false;
                })
                .catch(error => {
                    this.data = [];
                    this.data_all = [];
                    this.data_tags = [];
                    this.loading = false;
                    this.overlay = false;
                })
        },
    }
}
</script>