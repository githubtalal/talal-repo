<template >
    <Title :title="themeTitle" back="true"/>
    <div class="products-container">
    <el-row>
<!--      <ReservationProduct v-for="product in products" :key="product" v-if="productType === 'reservation' "/>-->
    <template v-for="product in products" :key="product">
      <ReservationProduct  v-if="product.type === 'reservation'" :product="product" class="product-card"/>
    </template>
        <template v-for="product in products" :key="product">
            <SimpleProduct v-if="product.type !== 'reservation'" :product="product" />
        </template>


    </el-row>

  </div>
    <el-button :disabled="cartStore.itemsCount == 0" id="cart-btn" @click="$router.push({name: 'Cart'})" class="cart-btn ge-light" type="primary">الذهاب إلى السلة ({{ cartStore.itemsCount}}) </el-button>

</template>

<script>
import ReservationProduct from "../components/ReservationProduct.vue";
import SimpleProduct from "../components/SimpleProduct.vue";
import Title from "../components/Title.vue";
import { useCartStore} from "../stores/cart";
import { useUserStore } from "../stores/user";

export default {
  name: "Products",
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
      let data = await this.$http.get(`/api/products/${this.id}?store_id=` + this.userStore.storeId);
      this.products = data.data.data;
        if(this.userStore.themeId == 1){
            this.themeTitle = 'الرسوم'
            console.log(this.themeTitle )

        }else {
            this.themeTitle = 'المنتجات'
            console.log(this.themeTitle )
        }
    },
  data(){
    return{
      products: [],
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
