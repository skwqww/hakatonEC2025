<script setup>

import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import { FloatLabel } from 'primevue'
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';

import { useAuthStore } from '@/stores/AuthStore.js'

const authStore = useAuthStore();



</script>

<template>
  <Toast/>
  <transition name="main">
    <div v-show="true" class="pt-14">
      <div v-if="true" class="bg-white w-full px-10 py-12">
          <h1 class="text-center text-3xl font-bold mb-10">Авторизация</h1>
        <form>
          <InputText class="mb-7 w-full" disabled :value="authStore.userName"/>
          <FloatLabel class="mb-2 w-full">
            <Password
              class="w-full"
              :inputClass="'w-full'"
              v-model="authStore.password" inputId="over_label" toggleMask :feedback="false"/>
            <label class="bg-transparent!" for="over_label">Пароль</label>
          </FloatLabel>
          <div class="flex items-center gap-2 mb-3">
            <Checkbox pt:box:class="h-5! w-5!" v-model="authStore.rememberMeStatus" inputId="rememberMe" binary/>
            <label class="text-mainText" for="rememberMe">Запомнить меня</label>
          </div>
          <div class="flex justify-center">
            <Button type="submit" label="Продолжить" @click="authStore.authUser()" :loading="authStore.buttonLoading"/>
          </div>
        </form>
      </div>
    </div>
  </transition>
</template>

<style scoped>

.main-enter-active {
  animation: main-enter 0.5s
}

@keyframes main-enter {
  0%{
    opacity: 0;
    transform: scale(0.9);
  }
  100%{
    opacity: 1;
    transform: scale(1);
  }
}

</style>
