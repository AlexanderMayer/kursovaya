<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useRouter} from 'vue-router';

const router = useRouter();
const route = useRoute();
let user = ref([]);

const data = async () => {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.post('http://localhost/kurs2.2/public/api/user/edit', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data.data;
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

function createLot() {
    const token = document.cookie.split('; ').find(row => row.startsWith('token='));
    if (!token) {
        router.push({ name: 'login' });
    } else {
        router.push({
            name: 'create',
        });
    }
}

onMounted(() => {
    data();
});

</script>

<template>
    <div class="container" v-show="user">
        <div class="row">
            <div class="col" v-if="user.avatar">
                <img class="rounded float-start w-75 h-75" :src="`../storage/${user.avatar}`" alt="Фото пользователя">
            </div>
            <div class="col" v-else>
                Нет аватарки
            </div>
            <div class="col">
                Имя: {{ user.name }}
            </div>
            <div class="col">
                Фамилия: {{ user.surname }}
            </div>
            <div class="col">
                Логин: {{ user.login }}
            </div>
            <div class="col">
                Роль: {{ user.role }}
            </div>
        </div>
    </div>
    <input type="submit" @click.prevent="createLot" class="btn btn-success m-3" value="Добавить лот">
</template>

<style scoped>

</style>
