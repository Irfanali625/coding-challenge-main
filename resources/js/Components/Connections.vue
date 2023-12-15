<template>
    <div class="my-2 shadow text-white bg-dark p-1" id="">
        <div class="d-flex justify-content-between" v-for="(item, i) in pagination" :key="i">
            <table class="ms-1">
                <td class="align-middle">{{ item.receiver.name }}</td>
                <td class="align-middle"> -</td>
                <td class="align-middle">{{ item.receiver.email }}</td>
                <td class="align-middle"></td>
            </table>
            <div>
                <button style="width: 220px" id="get_connections_in_common_"
                        @click.prevent="getCommonConnections(item.receiver_id)" class="btn btn-primary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false"
                        aria-controls="collapseExample">
                    Connections in common ()
                </button>
                <button id="create_request_btn_" @click.prevent="removeConnection(item.id)"
                        class="btn btn-danger me-1 my-1 mx-1">Remove Connection
                </button>
            </div>

        </div>
        <div class="collapse" id="collapse_">

            <div id="content_" class="p-2">
                <div class="p-2 shadow rounded mt-2  text-white bg-dark"
                     v-for="(item, i) in commonConnections" :key="i"
                >{{ item.name }} - {{ item.email }}
                </div>
            </div>
            <div id="connections_in_common_skeletons_">
            </div>
            <div class="d-flex justify-content-center w-100 py-2">
                <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_">Load
                    more
                </button>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from "vue";
import {toast} from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const connections = ref([]);
const commonConnections = ref([]);
const currentPage = ref(1);
const itemsPerPage = 10;
const totalSuggestions = ref(0);
const loader = ref(false);

function getConnections() {
    axios
        .get(`get-connections?page=${currentPage.value}&perPage=${itemsPerPage}`)
        .then((res) => {
            console.log(res.data);
            connections.value = [...connections.value, ...res.data.suggestions];
            totalSuggestions.value = res.data.suggestions.length;
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            loader.value = false;
        });
}

function removeConnection(id) {
    axios
        .post(`remove-connection-request`, {id: id})
        .then((res) => {
            console.log(res.data);
            toast.success(res.data.message);
        })
        .catch((error) => {
            console.error(error);
        });
}

function getCommonConnections(id) {
    axios.get(`get-common-connections/${id}`)
        .then((res) => {
            console.log(res.data);
            commonConnections.value = res.data.commonConnections;
            console.log(commonConnections.value);
        })
        .catch((error) => {
            console.error(error);
        });
}

function loadMore() {
    currentPage.value += 1;
    getConnections();
}

onMounted(() => {
    getConnections();
});

const pagination = ref([]);

watch(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    pagination.value = connections.value.slice(0, end);
});
</script>
