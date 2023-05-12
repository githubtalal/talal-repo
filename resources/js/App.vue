<script setup>
import {RouterLink, RouterView} from 'vue-router'
import {useCartStore} from "./stores/cart";
import {useUserStore} from "./stores/user";
import {onMounted} from "vue";
import axios from "axios";
import Loader from "./components/Loader";
const userStore = useUserStore();
const cartStore = useCartStore();

onMounted(async () => {
    let settings = await axios.get('/api/store-settings?store_id=' + userStore.storeId + '&user_id=' + userStore.userId);
    cartStore.initCart(settings.data)
    userStore.logo = settings.data.logo
})
</script>



<script>
import ThemeOne from "./views/Themes/ThemeOne";
import ThemeTwo from "./views/Themes/ThemeTwo";
import {useUserStore} from './stores/user';

export default {
    setup() {
      const userStore = useUserStore();

      return {userStore}
    },

    components:{ThemeOne,ThemeTwo},

    data(){
        return{
            Themes:['ThemeOne','ThemeTwo'],
            currentTheme:'',
        }
    },
    mounted() {
        // this.currentTheme = this.Themes[this.userStore.themeId]
        let prices = document.querySelectorAll('.ge-medium-numbers');
        for (let i =0 ; i<prices.length;i++){
            prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
        }
    }
}
</script>
<template>
    <ThemeTwo />
<!--    <component :is="Themes[parseInt(userStore.themeId)]" ></component>-->
    <Loader/>
</template>
<style scoped>

</style>
