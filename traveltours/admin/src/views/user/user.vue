<template>
  <div class="app-container">
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="ID" width="95">
        <template slot-scope="scope">
          {{ scope.$index }}
        </template>
      </el-table-column>
      <el-table-column label="Name" width="200">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column label="Username">
        <template slot-scope="scope">
          <span>{{ scope.row.username }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Email">
        <template slot-scope="scope">
          <span>{{ scope.row.email }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Avatar">
        <template slot-scope="scope">
          <el-image :src="scope.row.avatar" class="user-avatar" />
        </template>
      </el-table-column>
      <el-table-column label="Role">
        <template slot-scope="scope">
          <el-tag v-for="role in listRole(scope.row.roles)" :type="info">
            {{ role }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Status" class-name="status-col" width="100">
        <template slot-scope="{ row }">
          <el-tag :type="row.status | statusFilter">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column
        label="Actions"
        align="center"
        width="300"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="mini"
            @click="handleUpdate(scope.$index)"
          >
            Edit
          </el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index)"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="Edit User" :visible.sync="dialogFormVisible">
      <el-form
        ref="dataForm"
        :model="user"
        label-position="left"
        style="width: 400px; margin-left: 50px"
      >
        <el-form-item label="username" prop="title">
          <el-input v-model="user.username" disabled="true" />
        </el-form-item>
        <el-form-item label="Name" prop="title">
          <el-input v-model="user.name" />
        </el-form-item>
        <el-form-item label="Email" prop="title">
          <el-input v-model="user.email" />
        </el-form-item>
        <!-- <b-form-select v-model="user.status" class="mb-3" label="Status">
          <b-form-select-option value="active">Active</b-form-select-option>
          <b-form-select-option value="locked">Locked</b-form-select-option>
        </b-form-select> -->
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Cancel </el-button>
        <el-button
          v-if="!dialogCreate"
          type="primary"
          @click="updateUser(user)"
        >
          Update
        </el-button>
        <el-button
          v-if="dialogCreate"
          type="primary"
          @click="createCategory(category)"
        >
          Create
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getListUser, updateUser, deleteUser } from "@/api/user";
import { getAddressDetail } from "@/api/data";
// import "bootstrap/dist/js/bootstrap.js";
// import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        active: "success",
        locked: "danger",
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      dialogFormVisible: false,
      dialogCreate: false,
      list: null,
      user: null,
      listLoading: true,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.listLoading = true;
      getListUser().then((response) => {
        this.list = response.data;
        console.log(this.list);
        if (this.list.length > 0) {
          this.user = this.list[0];
        } else {
          this.user = {
            name: "",
            description: "",
          };
        }
        this.listLoading = false;
      });

      getAddressDetail().then((response) => {
        console.log(response);
      });
    },

    listRole(roles) {
      let result = [];
      if (roles.length > 0) {
        for (let i = 0; i < roles.length; i++) {
          result.push(roles[i].name);
        }
      }
      return result;
    },

    updateUser(user) {
      updateUser(user).then((response) => {
        if (response.code === 0) {
          this.$notify({
            message: "Update success",
            type: "success",
          });
          this.dialogFormVisible = false;
        }
      });
    },

    handleUpdate(index) {
      this.user = this.list[index];
      this.dialogFormVisible = true;
      this.dialogCreate = false;
    },

    handleDelete(index) {
      deleteUser({ id: this.list[index].id }).then((response) => {
        if (response.code === 0) {
          this.$notify({
            message: "Update success",
            type: "success",
          });
          this.fetchData();
        }
      });
    },

    changeStatus() {
      let currentStatus = this.user.status;
      if (currentStatus == "active") currentStatus = "locked";
      else currentStatus = "active";
    },
  },
};
</script>
<style lang="scss" scoped>
.user-avatar {
  cursor: pointer;
  width: 60px;
  height: 60px;
  border-radius: 10px;
}
</style>
