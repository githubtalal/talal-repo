<template>
    <Title title="العربة" back="true"/>
    <div class="container dir-rtl">
        <p class="ge-medium f-18 text-grey">الطلبات</p>
        <hr>
        <el-row class="my-1">
            <template v-for="item in cartStore.cartItems" :key="item">
                <ReservationCartItem v-if="item.product.type === 'reservation'" :product="item"/>
                <SimpleCartItem v-else :product="item" @qty="handleQty"/>
            </template>
        </el-row>
        <el-row>
            <template v-for="(totalLabel, totalKey) in totals" :key="totalKey">
               <template v-if="cartStore.cart[totalKey] && cartStore.cart[totalKey] > 0">
                   <el-col class="mb-2" :sm="6" :xs="12">
                       <p class="ge-medium text-grey f-18 f-14-m mt-0 mb-1/2 dir-rtl">
                           {{ totalLabel }}
                       </p>
                   </el-col>
                   <el-col class="mb-2" :sm="12" :xs="0">

                   </el-col>
                   <el-col class="mb-2" :sm="6" :xs="12">
                       <p class="text-red f-18 f-14-m ge-medium mt-0 mb-1 mr-1 dir-rtl text-center-m ge-medium-numbers">
                           {{ cartStore.cart['formatted_' + totalKey] }}
                       </p>
                   </el-col>
               </template>
            </template>
            <el-button @click="$router.push({name: 'Checkout'})" class="buy-btn ge-medium " type="primary"
                       :disabled="cartStore.cartItems == 0 ">إتمام عملية
                الشراء
            </el-button>
        </el-row>
    </div>
</template>

<script>

import {ref} from "vue";
import SimpleCartItem from "../components/SimpleCartItem.vue";
import ReservationCartItem from "../components/ReservationCartItem.vue";
import Title from "../components/Title.vue";
import {useUserStore} from '../stores/user'
import {useCartStore} from '../stores/cart'

export default {
    name: "Cart",
    components: {Title, ReservationCartItem, SimpleCartItem},
    setup() {
        const userStore = useUserStore();
        const cartStore = useCartStore();

        return {userStore, cartStore};
    },
    data() {
        return {
            cart: {},
            cartItems: [],
            simplePrice: ref(),
            reseverPrice: ref(),
            qty: '',
            totals: {
                'sub_total': 'المجموع الجزئي',
                'fees_total': 'أجور الخدمة',
                'tax_total': 'الضريبة',
                'total': 'المجموع الكلي',
            },
        }
    },
    methods: {
        async handleQty(cartItem, qty) {
            let response = await this.$http.get(`/api/update-qty/${cartItem.id}`, {
                params: {
                    quantity: qty,
                    store_id: this.userStore.storeId,
                    user_id: this.userStore.userId,
                }
            });
            this.cartStore.initCart(response.data);
        }
    },
    async mounted() {
        let data = await this.$http.get('/api/cart', {
            params: {
                'user_id': this.userStore.userId,
                'store_id': this.userStore.storeId,
            }
        });
        this.cart = data.data.cart;
        this.cartItems = data.data.items.data;
        let prices = document.querySelectorAll('.ge-medium-numbers');
        for (let i =0 ; i<prices.length;i++){
            prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
        }
    }
}
</script>

<style scoped>

</style>
