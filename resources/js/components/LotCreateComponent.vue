<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useRouter} from 'vue-router';

const router = useRouter();
const route = useRoute();
let categories = ref([]);

const data = async () => {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.post('http://localhost/kurs2.2/public/api/lots', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        categories.value = response.data;
        console.log(response);
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

onMounted(() => {
    data();
});

</script>

<template>
    <div class="w-25">
        <input type="text" v-model="title" class="form-control m-3" placeholder="Название лота">
        <input type="text" v-model="description" class="form-control m-3" placeholder="Описание">
        <input type="text" v-model="start_cost" class="form-control m-3" placeholder="Начальная стоимость">
        <input type="text" v-model="bet_step" class="form-control m-3" placeholder="Шаг ставки">
        <label>Выберите категорию
            <select class="form-control m-3">
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>
        </label>
        <input type="file" class="form-control m-3" placeholder="Фотографии">
        <input type="submit" @click.prevent="signUp" class="btn btn-success m-3" value="Выставить лот">
    </div>
</template>

<style scoped>

</style>
