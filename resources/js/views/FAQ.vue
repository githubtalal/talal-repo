<template>
    <Title title="الأسئلة الشائعة" back="true"/>
    <div class="cate-container">
        <div v-for="faq in faqs">
            <template v-if="isArabic(faq.question)">
               <h3 class="word-break  ge-bold dark-blue f-24 f-20-m mt-1 mb-1/2 dir-rtl w-100">{{faq.question}}</h3>
            </template>
            <template v-else>
                <h3 class="word-break  popp-bold dark-blue f-24 f-20-m mt-1 mb-1/2 w-100">{{faq.question}}</h3>
            </template>
            <template v-if="isArabic(faq.answer)">
                <p class="text-grey f-18 ge-medium mt-0 mb-1 dir-rtl word-break w-100">{{faq.answer}}</p>
            </template>
            <template v-else>
                <p class="text-grey f-18 popp-medium mt-0 mb-1 word-break w-100">{{faq.answer}}</p>
            </template>
        </div>
    </div>

</template>

<script>

import Title from "../components/Title";
import {useUserStore} from "../stores/user";
export default {
    name: "FAQ",
    components: {Title},
    setup() {
        const userStore = useUserStore();

        return {userStore};
    },
    data(){
        return{
            faqs:[],
        }
    },
    async mounted() {
        let data = await this.$http.get('/api/store/'+this.userStore.storeId+'/faq', {
            params: {
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId,
            }
        });
        this.faqs = JSON.parse(data.data.data[0])
        console.log(this.faqs);
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
