<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useRouter} from 'vue-router';

const router = useRouter();
const route = useRoute();
let role = ref('');


const data = async () => {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.get('http://localhost/kurs2.2/public/api/admin', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    } catch (error) {
        console.error('Ошибка данных', error);
        throw error;
    }
}

async function allLots() {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.post('http://localhost/kurs2.2/public/api/user/edit', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        role.value = response.data.data.role;
        if (role.value === '1') {
            router.push({name: 'login'});
        } else {
            router.push({
                name: 'admin.lots',
            });
        }
    } catch (error) {
        console.error('Ошибка просмотра лотов', error);
        throw error;
    }

}

onMounted(() => {
    data();
});

</script>

<template>
    <h1>Привет Админ</h1>
    <input type="submit" @click.prevent="allLots" class="btn btn-success m-3" value="Посмотреть лоты">
</template>

<style scoped>

</style>
