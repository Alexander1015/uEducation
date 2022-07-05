<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </v-main>
</template>

<script>
export default {
    name: 'PublicTopic',
    data: () => ({
        overlay: false,
        topic: {
            name: "",
            abstract: "",
            body: "",
            created_at: "",
            updated_at: "",
            img: "",
            lazy_img: "",
            user: "",
            user_update: "",
            subject: "",
            tags: [],
        },
    }),
    mounted() {
        this.getTopic();
    },
    methods: {
        async getTopic() {
            this.overlay = true;
            if (this.$route.params.subject && this.$route.params.topic) {
                await this.axios.get('/api/gettopic/' + this.$route.params.topic)
                    .then(response => {
                        const item = response.data;
                        if (!item.topic) {
                            this.overlay = false;
                            this.$router.push({ name: "publicSubject", params: { subject: this.$route.params.subject } });
                        }
                        else {
                            this.topic.name = item.topic.name;
                            this.topic.abstract = item.topic.abstract;
                            this.topic.user = item.topic.user;
                            this.topic.user_update = item.topic.user_update;
                            this.topic.subject = item.topic.subject;
                            this.topic.created_at = item.topic.created_at;
                            this.topic.updated_at = item.topic.updated_at;
                            this.topic.body = item.topic.body;
                            if (item.topic.img) {
                                this.topic.img = "/img/topics/" + item.topic.img;
                                this.topic.lazy_img = "/img/lazy_topics/" + item.topic.img;
                            }
                            else {
                                this.topic.img = "/img/topic/blank.png";
                                this.topic.lazy_img = "/img/lazy_topics/blank.png";
                            }
                            if(item.tags) {
                                this.topic.tags = item.tags;
                            }
                        }
                        this.overlay = false;
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                        this.$router.push("/");
                    });
            }
            else if (this.$route.params.subject && !this.$route.params.topic) {
                this.overlay = false;
                this.$router.push({ name: "publicSubject", params: { subject: this.$route.params.subject } });
            }
            else {
                this.overlay = false;
                this.$router.push("/");
            }
        }
    },
}
</script>