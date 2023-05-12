<template>
    <Title :title="themeTitle" back="true"/>
    <div class="product-item-container">
        <template v-if="product.type === 'reservation'">
            <ReservationProduct :product="product" class="product-card"/>
        </template>
        <template  v-if="product.type !== 'reservation'" >
            <SimpleProduct :product="product" :counter="true"/>
        </template>


    </div>


    <el-button :disabled="cartStore.itemsCount == 0" id="cart-btn" @click="$router.push({name: 'Cart'})" class="cart-btn ge-light" type="primary">الذهاب إلى السلة ({{ cartStore.itemsCount}}) </el-button>
</template>

<script>
import { useCartStore} from "../stores/cart";
import { useUserStore } from "../stores/user";
import ReservationProduct from "../components/ReservationProduct";
import SimpleProduct from "../components/SimpleProduct";
import Title from "../components/Title";

export default {
    name: "ProductItem",
    components: {Title, SimpleProduct, ReservationProduct},
    props:{
        id: undefined
    },
    setup() {
        const cartStore = useCartStore();
        const userStore = useUserStore();

        return {cartStore, userStore}
    },
    async mounted() {
        console.log(this.id)
        let data = await this.$http.get(`/api/product/${this.id}`);
        console.log(data);
        this.product = data.data.data;
        this.themeTitle =this.product.name
        this.productType =this.product.type
    },
    data(){
        return{
            product: '',
            productType:'reservation',
            themeTitle:'',
        }
    },
    created () {
        window.addEventListener('scroll', this.isBottom);
    },
    destroyed () {
        window.removeEventListener('scroll', this.isBottom);
    },
    methods: {
        isBottom() {
            if (this.userStore.themeId == 1) {
                window.onscroll = function (ev) {
                    if (document.getElementById('cart-btn')) {
                        if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 50)) {
                            document.getElementById('cart-btn').style.bottom = '50px';
                        } else {
                            document.getElementById('cart-btn').style.bottom = '0';
                        }
                    }
                };
            }
        },
    },
}
</script>

<style scoped>

</style>
