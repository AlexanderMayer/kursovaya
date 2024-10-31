<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Cookies from "js-cookie";
import { thisUrl } from "../../api.js";

const router = useRouter();
let complaints = ref([]);
const searchQuery = ref('');
const showSuccessModal = ref(false);

const data = async () => {
    try {
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/admin/complaints`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        complaints.value = response.data.map(complaint => markComplaintAsViewed(complaint));

    } catch (error) {
        console.error('Ошибка вывода жалоб', error);
        throw error;
    }
};

const filteredComplaints = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return complaints.value.filter(complaint =>
        complaint.author.name.toLowerCase().includes(query) ||
        complaint.author.surname.toLowerCase().includes(query) ||
        complaint.author.login.toLowerCase().includes(query) ||
        complaint.target.name.toLowerCase().includes(query) ||
        complaint.target.surname.toLowerCase().includes(query) ||
        complaint.target.login.toLowerCase().includes(query)
    );
});

const markComplaintAsViewed = (complaint) => {
    return {
        ...complaint,
        isViewed: complaint.viewed === 1
    };
};

const submitDecision = async (complaint) => {
    try {
        const token = Cookies.get('token');

        await axios.post(`${thisUrl()}/admin/users/${complaint.target.id}/${complaint.id}/change_rating`, {
            id: complaint.id,
            viewed: complaint.viewed,
            decision: complaint.decision
        }, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        await data();
        showSuccessModal.value = true;
        setTimeout(() => {
            showSuccessModal.value = false;
        }, 3000);

    } catch (error) {
        console.error('Ошибка отправки решения', error);
    }
};

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container">
        <h2 class="my-4 text-center text-md-start">Список жалоб</h2>
        <input type="text" v-model="searchQuery" placeholder="Искать по имени, фамилии или логину..." class="form-control mb-3" />

        <div class="row">
            <div v-for="complaint in filteredComplaints" :key="complaint.id" class="col-12 mb-3 complaint-item card" :class="{ 'bg-lightgray': complaint.isViewed }">
                <div class="card-body d-md-flex">
                    <div class="fixed-section w-100 w-md-25">
                        <h5>Отправитель:</h5>
                        <p><strong>Имя:</strong> {{ complaint.author.name }}</p>
                        <p><strong>Фамилия:</strong> {{ complaint.author.surname }}</p>
                        <p><strong>Логин:</strong> {{ complaint.author.login }}</p>
                    </div>

                    <div class="fixed-section w-100 w-md-25 mt-3 mt-md-0">
                        <h5>Продавец:</h5>
                        <p><strong>Имя:</strong> {{ complaint.target.name }}</p>
                        <p><strong>Фамилия:</strong> {{ complaint.target.surname }}</p>
                        <p><strong>Логин:</strong> {{ complaint.target.login }}</p>
                    </div>

                    <div class="fixed-section w-100 w-md-50 mt-3 mt-md-0">
                        <h5>Описание жалобы:</h5>
                        <p>{{ complaint.content }}</p>
                    </div>

                    <div class="action-section d-flex flex-column align-items-end p-2 w-100 w-md-auto mt-3 mt-md-0">
                        <select v-model="complaint.viewed" class="form-select mb-2">
                            <option value="0">Не просмотрено</option>
                            <option value="1">Просмотрено</option>
                        </select>
                        <select v-model="complaint.decision" class="form-select mb-2">
                            <option value="0">Не виновен</option>
                            <option value="1">Виновен</option>
                        </select>
                        <button class="btn btn-primary" @click="() => submitDecision(complaint)">Отправить решение</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showSuccessModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Успех</h5>
                    </div>
                    <div class="modal-body">
                        <p>Решение принято успешно!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.complaint-item {
    display: flex;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.fixed-section {
    padding: 10px;
    border-right: 1px solid #ddd;
}

.bg-lightgray {
    background-color: #f0f0f0 !important;
}

.modal.fade.show.d-block {
    opacity: 1;
    transition: opacity 1s ease-in-out;
}

.container {
    max-width: 1820px;
}

@media (max-width: 800px) {
    .fixed-section {
        border-right: none;
    }
    .complaint-item {
        flex-direction: column;
        padding: 10px;
    }
    .action-section {
        align-items: stretch;
    }
}
</style>


