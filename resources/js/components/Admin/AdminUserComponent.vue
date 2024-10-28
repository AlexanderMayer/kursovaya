<script setup>
import { onMounted, ref } from 'vue';
import Cookies from "js-cookie";
import axios from "axios";
import { useRoute } from 'vue-router';

const route = useRoute();
let user = ref([]);

const data = async () => {
    try {
        const userId = route.params.id;
        const token = Cookies.get('token');
        const response = await axios.post(`http://localhost/kurs2.2/public/api/admin/users/${userId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data;

    } catch (error) {
        console.error('Ошибка вывода', error);
    }
};

const userBan = async (userId) => {
    try {
        const token = Cookies.get('token');
        await axios.post(`http://localhost/kurs2.2/public/api/admin/users/${userId}/ban/`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        alert('Пользователь заблокирован');
        await data();
    } catch (error) {
        console.error('Ошибка при блокировке пользователя', error);
        alert('Ошибка при блокировке пользователя, попробуйте ещё раз!');
    }
};

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container my-4" v-show="user">
        <div class="row">

            <div class="col-md-3 d-flex align-items-center">
                <div class="w-100">
                    <div v-if="user.avatar">
                        <img class="rounded user-avatar" :src="`/storage/${user.avatar}`" alt="Фото пользователя">
                    </div>
                    <div v-else>
                        <p>Нет аватарки</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h3>{{ user.name }} {{ user.surname }}</h3>
                <p><strong>Логин:</strong> {{ user.login }}</p>

                <div class="my-3">
                    <h5>Рейтинг пользователя:</h5>
                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            :class="{
                                'bg-danger': user.rate_decency <= 25,
                                'bg-warning': user.rate_decency > 25 && user.rate_decency <= 50,
                                'bg-info': user.rate_decency > 50 && user.rate_decency <= 75,
                                'bg-success': user.rate_decency > 75
                            }"
                            :style="{ width: user.rate_decency + '%' }"
                            :aria-valuenow="user.rate_decency"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            {{ user.rate_decency }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-evenly">
                <button @click.prevent="userBan(user.id)" class="btn btn-danger mb-2">Заблокировать</button>
            </div>

        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 1820px;
}

.progress {
    max-width: 300px;
    height: 25px;
    border-radius: 0.5rem;
}

.progress-bar {
    line-height: 25px;
    font-size: 1rem;
}

h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.user-avatar {
    width: 100%;
    height: auto;
}

@media (max-width: 800px) {
    .user-avatar {
        max-height: 200px;
        object-fit: scale-down;
    }
}
</style>
