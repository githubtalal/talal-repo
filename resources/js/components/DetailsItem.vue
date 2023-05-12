<template>
  <el-col class="my-2" :xs="24" :sm="24" v-for="item in cartItems" :key="item">
    <div class="details-item" >
      <el-row class="h-full" >
          <el-col class="d-flex just-center align-center pr-2 pr-1-m " :xs="8" :sm="7">
              <img class="w-full cart-img object-contain p-half" :src="item.product.cover" @error="placeholderImg"/>
          </el-col>
        <el-col class="d-flex just-center flex-dir-col border-left" :xs="5" :sm="7">
          <p class="ge-medium text-center text-grey m-0 mb-1/2 f-12-m ">
            {{ item.product.name }}
          </p>
          <p class="ge-medium text-grey m-0 ">

          </p>
        </el-col>
        <el-col class="d-flex just-center align-center pr-2 pr-1-m" :xs="8" :sm="7">
          <p class="text-grey f-18 f-12-m ge-medium mt-0 mb-1 dir-rtl ge-medium-numbers ">
              {{  item.formatted_price }}
              </p>
        </el-col>
        <el-col :xs="3" :sm="3">
            <div class="trash-back d-flex just-center align-center cursor-pointer">
                <WarningModal :product="item" modalTitle="هل أنت متأكد؟" modalBody="هل انت متأكد من حذفك للمنتج  "/>
            </div>
<!--          <div @click="removeFromCart(item)" class="trash-back d-flex just-center align-center cursor-pointer">-->
<!--            <img src="../assets/img/DeleteIcon.svg" class="cursor-pointer trash-img"/>-->
<!--          </div>-->
        </el-col>
      </el-row>
    </div>
  </el-col>
</template>

<script>
import {useCartStore} from "../stores/cart";
import {useUserStore} from "../stores/user";
import WarningModal from "./WarningModal";
import {ElMessageBox} from "element-plus";

export default {
  name: "DetailsItem",
    components: {WarningModal},
    setup() {
        const cartStore = useCartStore();
        const userStore = useUserStore();
        return {cartStore, userStore};
    },
    props: {
      cartItems: []
    },
    methods: {
        placeholderImg(event){
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        },
        async removeFromCart(cartItem) {
            document.getElementById('loader').classList.remove('d-none');
            if (!cartItem) return;
            let response = await this.$http.get(`/api/removeFromCart/${cartItem.id}`, {
                params: {
                    'user_id': this.userStore.userId,
                    'store_id': this.userStore.storeId,
                }
            });
            if (response.data.success) {
                document.getElementById('loader').classList.add('d-none');
                this.cartStore.initCart(response.data);
                // this.cartStore.removeItem(cartItem);
            }
            else{
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
            }
        }
    },
    mounted(){
        console.log(this.product)
        let prices = document.querySelectorAll('.ge-medium-numbers');
        for (let i =0 ; i<prices.length;i++){
            prices[i].innerHTML  = prices[i].innerHTML.replace(/(\d+)/g, '<span class="numbers">$1</span>')
        }
    },
}
</script>

<style scoped>

</style>
