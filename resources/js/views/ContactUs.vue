<template>
    <Title title="تواصل معنا" back="true"/>
    <div class="cate-container">
        <template v-if="isArabic(contactUs)">
            <h3 class="word-break ge-bold dark-blue f-20 mt-1 mb-1/2 dir-rtl w-100">{{contactUs}}</h3>
        </template>
        <template v-else>
            <h3 class="word-break popp-bold dark-blue f-20 mt-1 mb-1/2 w-100 ">{{contactUs}}</h3>
        </template>
    </div>
</template>

<script>

import Title from "../components/Title";
import {useUserStore} from "../stores/user";
export default {
    name: "ContactUs",
    components: {Title},
    setup() {
        const userStore = useUserStore();

        return {userStore};
    },
    data(){
        return{
            contactUs:[],
        }
    },
    async mounted() {
        let data = await this.$http.get('/api/store/'+this.userStore.storeId+'/contact-us', {
            params: {
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId,
            }
        });
        this.contactUs = JSON.parse(data.data.data[0])
        console.log(data);
    },
    methods:{
        isArabic(text){
            let arabic = /[\u0600-\u06FF\u0750-\u077F]/;
            let result = arabic.test(text);
            return result;
        }
    }
}
</script>

<style scoped>

</style>
