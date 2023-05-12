<template>
    <Title title="من نحن" back="true"/>
    <div class="cate-container">
        <template v-if="isArabic(whoAreWe)">
            <h3 class="ge-light dark-blue f-20 mt-1 mb-1/2 dir-rtl word-break lh-30 ">{{whoAreWe}}</h3>
        </template>
        <template v-else>
            <h3 class="popp-medium dark-blue f-20 mt-1 mb-1/2 word-break lh-30 ">{{whoAreWe}}</h3>
        </template>
    </div>
</template>

<script>

import Title from "../components/Title";
import {useUserStore} from "../stores/user";
export default {
    name: "WhoAreWe",
    components: {Title},
    setup() {
        const userStore = useUserStore();

        return {userStore};
    },
    data(){
        return{
            whoAreWe: '',
        }
    },
    async mounted() {
        let data = await this.$http.get('/api/store/'+this.userStore.storeId+'/who-are-we', {
            params: {
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId,
            }
        });
        this.whoAreWe = JSON.parse(data.data.data[0])
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
