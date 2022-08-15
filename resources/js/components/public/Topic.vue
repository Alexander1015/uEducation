<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <div class="ml-16">
            <template v-if="overlay == false">
                <v-card class="mt-2 mx-auto" elevation="2" max-width="1200">
                    <!-- Imagen -->
                    <v-img class="mx-auto d-none d-sm-flex" :src='topics.img' :lazy-src='topics.lazy_img'
                        max-height="250">
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5">
                                </v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                    <!-- Titulo -->
                    <v-card-title>
                        <span class="text-h5 text-uppercase font-weight-bold mx-1">
                            {{ topics.name }}
                        </span>
                    </v-card-title>
                    <!-- Tags -->
                    <template v-if="topics.tags.length > 0">
                        <div class="d-none d-md-flex mt-n1 mx-6">
                            <span class="subtitle-2 font-italic">
                                Etiqueta/s:
                            </span>
                            <template v-for="item in topics.tags">
                                <v-chip class="ml-2 mr-1 mb-1 mt-n1" :color="item.background_color"
                                    :style='"color:" + item.text_color + ";"'>
                                    {{ item.name }}
                                </v-chip>
                            </template>
                        </div>
                    </template>
                    <!-- Usuario -->
                    <template v-if="user.name">
                        <v-card-text class="mt-0">
                            <v-list-item>
                                <v-list-item-avatar>
                                    <v-img :src="user.avatar" max-height="38" max-width="38"
                                        :lazy-src="user.lazy_avatar">
                                        <template v-slot:placeholder>
                                            <v-row class="fill-height ma-0" align="center" justify="center">
                                                <v-progress-circular indeterminate color="grey lighten-5">
                                                </v-progress-circular>
                                            </v-row>
                                        </template>
                                    </v-img>
                                </v-list-item-avatar>
                                <v-list-item-title>
                                    <span class="subtitle-1 font-italic font-weight-bold">
                                        {{ user.name }}
                                    </span>
                                    <br />
                                    <span class="subtitle-2 font-italic">
                                        Contacto:
                                        <a :href="'mailto:' + (user.email)">
                                            {{ user.email }}
                                        </a>
                                    </span>
                                    <br />
                                    <span class="subtitle-2 font-italic">
                                        Ultima actualización: {{ user.date }}
                                    </span>
                                </v-list-item-title>
                            </v-list-item>
                        </v-card-text>
                    </template>
                    <!-- Abstract -->
                    <template v-if="topics.abstract">
                        <div class="d-none d-md-flex">
                            <v-divider></v-divider>
                            <v-card-text>
                                <div id="data_abstract" class="bk_tags_bk txt_black mx-2 pa-2">
                                    <span class="body-2">
                                        {{ topics.abstract }}
                                    </span>
                                </div>
                            </v-card-text>
                        </div>
                    </template>
                    <!-- Divider -->
                    <v-divider></v-divider>
                    <!-- Body -->
                    <div id="data_body" class="mt-4 mx-3">
                        <ckeditor :editor="editor" v-model="topics.body" :config="editorConfig"
                            :disabled="editorDisabled">
                        </ckeditor>
                    </div>
                    <!-- Boton regresar y avanzar -->
                    <template v-if="previousTopic.slug || nextTopic.slug">
                        <div class="py-2 px-4">
                            <v-row>
                                <template v-if="previousTopic.slug">
                                    <v-col cols="12" sm="6" md="4" class="mx-auto">
                                        <v-btn class="width_100 py-4" height="50" plain small
                                            :to='{ name: "publicTopic", params: { topic: previousTopic.slug } }'>
                                            <v-icon left>keyboard_double_arrow_left</v-icon>
                                            <span>
                                                Anterior
                                                <br />
                                                <small>
                                                    {{ previousTopic.name }}
                                                </small>
                                            </span>
                                        </v-btn>
                                    </v-col>
                                </template>
                                <template v-if="nextTopic.slug">
                                    <v-col cols="12" sm="6" md="4" class="mx-auto">
                                        <v-btn class="width_100 py-4" height="50" plain small
                                            :to='{ name: "publicTopic", params: { topic: nextTopic.slug } }'>
                                            <span>
                                                Siguiente
                                                <br />
                                                <small>
                                                    {{ nextTopic.name }}
                                                </small>
                                            </span>
                                            <v-icon right>keyboard_double_arrow_right</v-icon>
                                        </v-btn>
                                    </v-col>
                                </template>
                            </v-row>
                        </div>
                    </template>
                </v-card>
            </template>
            <template v-else>
                <v-row>
                    <v-col cols="12" class="mt-2 mx-auto">
                        <v-skeleton-loader v-bind="attrs" type="list-item-avatar-three-line, image, article">
                        </v-skeleton-loader>
                    </v-col>
                </v-row>
            </template>
        </div>
    </v-main>
</template>

<script>
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import '@ckeditor/ckeditor5-build-classic/build/translations/es';

export default {
    name: 'PublicTopic',
    data: () => ({
        editor: DecoupledEditor,
        editorDisabled: true,
        editorConfig: {
            language: 'es',
            placeholder: '',
        },
        overlay: false,
        attrs: {
            class: 'mb-6',
            elevation: 2,
            loading: true,
        },
        topics: {
            name: "",
            abstract: "",
            body: "",
            img: "",
            lazy_img: "",
            subject: "",
            tags: [],
        },
        user: {
            name: "",
            email: "",
            date: "",
            avatar: "",
            avatar: "",
            lazy_avatar: "",
        },
        previousTopic: {
            slug: "",
            name: "",
        },
        nextTopic: {
            slug: "",
            name: "",
        },
    }),
    mounted() {
        this.getTopic();
    },
    computed: {
        address() {
            return this.$route.params.topic;
        }
    },
    watch: {
        address() {
            this.getTopic();
        }
    },
    methods: {
        async getTopic() {
            this.overlay = true;
            if (this.$route.params.subject && this.address) {
                await this.axios.get('/api/gettopic/' + this.address)
                    .then(response => {
                        const item = response.data;
                        if (!item.topic || item.topic.subject_slug != this.$route.params.subject) {
                            this.$router.push({ name: "error" });
                        }
                        else {
                            this.topics.name = item.topic.name;
                            this.topics.abstract = item.topic.abstract;
                            this.topics.subject = item.topic.subject;
                            this.topics.body = item.topic.data;
                            // Users
                            if (item.topic.user_update && item.topic.user_update_status == 1) {
                                this.user.name = item.topic.user_update;
                                this.user.email = item.topic.user_update_email;
                                this.user.date = item.topic.updated_at;
                                // Imagenes
                                if (item.topic.user_update_avatar) {
                                    this.user.avatar = "/img/users/" + item.topic.user_update_avatar + "/index.png";
                                    this.user.lazy_avatar = "/img/users/" + item.topic.user_update_avatar + "/lazy.png";
                                }
                                else {
                                    this.user.avatar = "/img/users/blank.png";
                                    this.user.lazy_avatar = "/img/users/blank_lazy.png";
                                }
                            }
                            else if (item.topic.user && item.topic.user_status == 1) {
                                this.user.name = item.topic.user;
                                this.user.email = item.topic.user_email;
                                this.user.date = item.topic.updated_at ? item.topic.updated_at : item.topic.created_at;
                                // Imagenes
                                if (item.topic.user_avatar) {
                                    this.user.avatar = "/img/users/" + item.topic.user_avatar + "/index.png";
                                    this.user.lazy_avatar = "/img/users/" + item.topic.user_avatar + "/lazy.png";
                                }
                                else {
                                    this.user.avatar = "/img/users/blank.png";
                                    this.user.lazy_avatar = "/img/users/blank_lazy.png";
                                }
                            }
                            else {
                                this.user.name = "";
                                this.user.email = "";
                                this.user.date = "";
                                this.user.avatar = "/img/users/blank.png";
                                this.user.lazy_avatar = "/img/users/blank_lazy.png";
                            }
                            // Imagen del topics
                            if (item.topic.img) {
                                this.topics.img = "/img/topics/" + item.topic.img + "/index.png";
                                this.topics.lazy_img = "/img/topics/" + item.topic.img + "/lazy.png";
                            }
                            else {
                                this.topics.img = "/img/topics/blank.png";
                                this.topics.lazy_img = "/img/topics/blank_lazy.png";
                            }
                            // Anterior
                            if (item.previous.slug) {
                                this.previousTopic.slug = item.previous.slug;
                                this.previousTopic.name = item.previous.name;
                            }
                            else {
                                this.previousTopic.slug = "";
                                this.previousTopic.name = "";
                            }
                            //Siguiente
                            if (item.next.slug) {
                                this.nextTopic.slug = item.next.slug;
                                this.nextTopic.name = item.next.name;
                            }
                            else {
                                this.nextTopic.slug = "";
                                this.nextTopic.name = "";
                            }
                            // Tags
                            if (item.tags) this.topics.tags = item.tags;
                            else this.topics.tags = [];
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.$router.push({ name: "error" });
                    });
            }
            else {
                this.$router.push({ name: "error" });
            }
        }
    },
}
</script>