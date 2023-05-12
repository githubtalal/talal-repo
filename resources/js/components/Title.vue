<template>
  <div class="bg-dark-blue just-center d-flex align-center h-60" >
    <a v-if="back === 'true'" class="px-4 cursor-pointer" @click="$router.go(-1)">
      <img src="../assets/img/Left_Icon.svg"/>
    </a>
    <h3 class="w-full white ge-medium text-center m-0 f-18-m">{{ title }}</h3>
      <el-popover class="menu-poper" placement="bottom" trigger="hover" v-if="back === 'true'">
          <template #reference>
              <el-icon class="expand-icon mr-1" :class="back === 'true' ? 'ml-3': '' "><Expand /></el-icon>
          </template>
          <ul id="store_menu" class="d-flex just-center align-center flex-dir-col">
              <li>
                  <router-link :to="{name:'Categories'}">
                      الفئات
                  </router-link>
              </li>
              <li>
                  <router-link v-show="userStore.storeInfo.has_faq" :to="{name:'FAQ'}">
                      الأسئلة الشائعة
                  </router-link>
              </li>
              <li>
                  <router-link v-show="userStore.storeInfo.has_who_are_we" :to="{name:'WhoAreWe'}">
                      من نحن
                  </router-link>
              </li>
              <li>
                  <router-link v-show="userStore.storeInfo.has_contact_us" :to="{name:'ContactUs'}">
                      تواصل معنا
                  </router-link>
              </li>

          </ul>
      </el-popover>
  </div>
</template>

<script>
import {Expand} from '@element-plus/icons-vue'
import {useUserStore} from "../stores/user";

export default {
  name: "Title",
    setup(){
        const userStore = useUserStore();
        return {userStore}
    },
    components:{
      Expand
    },
  data(){
    return{
    }
  },
  props:[
      'title',
      'back',
  ],
}
</script>

<style scoped>

</style>
