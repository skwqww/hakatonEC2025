<script setup>

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputMask from 'primevue/inputmask';
import Button from 'primevue/button'

import { onMounted, ref } from 'vue'

import { useAdminStore } from '@/stores/AdminStore.js'
import { usePreloaderStore } from '@/stores/PreloaderStore.js'

import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Select from 'primevue/select';

import Dialog from 'primevue/dialog';

const adminStore = useAdminStore()
const preloaderStore = usePreloaderStore()

const modalStatus  = ref(false)


onMounted(() => {
  adminStore.getUsersData()
})


</script>

<template>
  <div>
  <div v-if="!preloaderStore.adminPreloaderStatus">

    <Button label="Создать" @click="modalStatus = true"/>

      <Dialog v-model:visible="modalStatus" modal header="Пользователь" :style="{ width: '25rem' }">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">Создание нового пользователя</span>
        <div class="flex items-center gap-4 mb-4">
          <label for="name" class="font-semibold w-24">Имя</label>
          <InputText v-model="adminStore.createUserData.name" id="name" class="flex-auto w-full" autocomplete="off" />
        </div>
        <div class="flex items-center gap-4 mb-4">
          <label for="username" class="font-semibold w-24">Имя@</label>
          <InputText v-model="adminStore.createUserData.username" id="username" class="flex-auto w-full" autocomplete="off" />
        </div>
        <div class="flex items-center gap-4 mb-8">
          <label for="password" class="font-semibold w-24">Пароль</label>
          <Password v-model="adminStore.createUserData.password" style="width: max-content" class="w-full" if="password" toggleMask />
        </div>
        <div class="flex items-center gap-4 mb-8">
          <label for="role" class="font-semibold w-24">Роль</label>
          <InputText v-model="adminStore.createUserData.role" id="role" class="flex-auto w-full" autocomplete="off" />
        </div>
        <div class="flex items-center gap-4 mb-8">
          <label for="prefix" class="font-semibold w-24">Префикс</label>
          <InputText v-model="adminStore.createUserData.prefix" id="prefix" class="flex-auto w-full" autocomplete="off" />
        </div>
        <div class="flex justify-end gap-2">
          <Button type="button" label="Отмена" severity="secondary" @click="modalStatus = false"></Button>
          <Button type="button" label="Продолжить" @click="adminStore.createUserQuery(), modalStatus = false"></Button>
        </div>
      </Dialog>


    <DataTable
      editMode="cell" @cell-edit-complete="adminStore.onEditUser"
      :rows="10"
      :value="adminStore.usersData"
      tableStyle="min-width: 50rem"
      :pt="{
        table: { style: 'min-width: 47rem' },
        column: {
            bodycell: ({ state }) => ({
                class: [{ '!py-0': state['d_editing'] }]
            })
        }
    }"
    >
      <Column :exportable="false" style="min-width: 5rem">
        <template #body="slotProps">
          <Button outlined rounded severity="danger" icon="pi pi-trash" @click="adminStore.onRemoveUser(slotProps.data)" />
        </template>
      </Column>

      <Column removableSort sortable field="id" header="Id">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
      </Column>

      <Column removableSort sortable field="name" header="Имя">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" autofocus fluid />
        </template>
      </Column>

      <Column removableSort sortable field="username" header="Имя@">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
      </Column>

      <Column removableSort sortable field="password" header="Пароль">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" autofocus fluid />
        </template>
      </Column>

      <Column removableSort sortable field="prefix" header="Префикс">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" autofocus fluid />
        </template>
      </Column>

      <Column field="start_work" header="Начало работы">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
        <template #editor="{ data, field }">
          <InputMask style="width: 70px" id="basic" v-model="data[field]" mask="99:99" placeholder="00:00" />
        </template>
      </Column>

      <Column field="end_work" header="Конец работы">
        <template #body="{ data, field }">
          {{ data[field] }}
        </template>
        <template #editor="{ data, field }">
          <InputMask style="width: 70px" id="basic" v-model="data[field]" mask="99:99" placeholder="00:00" />
        </template>
      </Column>

    </DataTable>
  </div>
  </div>
</template>

<style scoped>


</style>
