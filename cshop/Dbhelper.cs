using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data;
using MySql.Data.MySqlClient;
using System.Collections;

namespace puruite.Helps
{
    /**
     *   数据库操作类
     *  Donald
     *  普瑞特
     */
    class Dbhelper
    {
        private static MySqlConnection _prtconn = null;
        private static string constructorString = "server=127.0.0.1;User Id=root;password=123456;Database=puruite_client_development";

        #region open and close conn
        /// <summary>
        /// 链接mysql
        /// </summary>
        /// <returns></returns>
        private static MySqlConnection GetConn()
        {
           
            _prtconn = new MySqlConnection(constructorString);
            return _prtconn;
        }

        /// <summary>
        /// 打开链接
        /// </summary>
        private static void OpenConn()
        {
            if (_prtconn.State == ConnectionState.Closed)
            {
                _prtconn.Open();
            } else
            {
                _prtconn.Close();
                _prtconn.Open();
            }
        }

        /// <summary>
        /// 关闭数据库连接
        /// </summary>
        /// <param name="prtconn">数据库链接</param>
        private static void CloseConn()
        {
            if (_prtconn.State == ConnectionState.Open)
            {
                _prtconn.Close();
                _prtconn.Dispose();
            }
        }
        #endregion

        #region select DataSet
        /// <summary>
        /// 
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <returns></returns>
        public static DataSet GetDataSetSql(string sqlStr)
        {
            return GetDataSet(sqlStr, null, CommandType.Text);
        }

        /// <summary>
        /// 查询，返回DataSet对象
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <param name="paras">参数</param>
        /// <returns></returns>
        public static DataSet GetDataSetSqlParameter(string sqlStr, MySqlParameter[] paras)
        {
            return GetDataSet(sqlStr, paras, CommandType.Text);
        }

        /// <summary>
        /// 查询，返回DataSet对象
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <param name="paras">参数</param>
        /// <param name="type"></param>
        /// <returns></returns>
        private static DataSet GetDataSet(string sqlStr, MySqlParameter[] paras, CommandType type)
        {
            DataSet ds = new DataSet();
            using (MySqlConnection conn = GetConn())
            {
                MySqlDataAdapter ada = new MySqlDataAdapter(sqlStr, conn);
                ada.SelectCommand.CommandType = type;
                if (paras != null)
                {
                    ada.SelectCommand.Parameters.AddRange(paras);
                }
                ada.Fill(ds);
            }
            return ds;
        }
        #endregion


        #region select row by id
        /// <summary>
        ///  根据id 获取1行数据
        /// </summary>
        /// <param name="tableName">表</param>
        /// <param name="id">id</param>
        /// <param name="columns">要查询的字段，为了提高查询效率，禁止用*</param>
        /// <returns></returns>
        public static DataRow GetRowById(string tableName, int id, string[] columns)
        {
            if (tableName == null)
            {
                return null;
            }
            if (columns.Length == 0)
            {
                return null;
            }
            string sqlStr = string.Format("SELECT {0} FROM {1} WHERE id={2}", string.Join(",", columns), tableName, id);
            DataTable dataTable = GetDataTableSql(sqlStr);
            if (dataTable != null)
            {
                return dataTable.AsEnumerable().First<DataRow>();
            }
            return null;
        }
        /// <summary>
        /// 根据id获取1行数据
        /// </summary>
        /// <typeparam name="T">表</typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <param name="id"></param>
        /// <returns></returns>
        public static DataRow GetRowById<T>(T obj, string tableName, int id)
        {
            if (tableName == null)
            {
                return null;
            }
            string fields = GetFields(obj);
            if (fields == null)
            {
                return null;
            }
            string sqlStr = string.Format("SELECT {0} FROM {1} WHERE id={2}", fields, tableName, id);
            DataTable dataTable = GetDataTableSql(sqlStr);
            if (dataTable != null)
            {
                return dataTable.AsEnumerable().First<DataRow>();
            }
            return null;
        }
        #endregion


        #region select row by condition
        /// <summary>
        /// 根据条件获取1行数据
        /// </summary>
        /// <param name="tableName"></param>
        /// <param name="columns"></param>
        /// <param name="conditions"></param>
        /// <returns></returns>
        public static DataRow GetRow(string tableName, string[] columns, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return null;
            }
            if (columns.Length == 0)
            {
                return null;
            }
            if (conditions.Count == 0)
            {
                return null;
            }
            string conditionStr = GetUpdateConditions(conditions);

            string sqlStr = string.Format("SELECT {0} FROM {1} WHERE {2}", string.Join(",", columns), tableName, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            DataTable dataTable = GetDataTableSqlParameter(sqlStr, paras.ToArray());
            if (dataTable != null)
            {
                return dataTable.AsEnumerable().First<DataRow>();
            }
            return null;
        }
        /// <summary>
        /// 根据条件获取1行数据
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <param name="conditions"></param>
        /// <returns></returns>
        public DataRow GetRow<T>(T obj, string tableName, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return null;
            }
            if (conditions.Count == 0)
            {
                return null;
            }
            string fields = GetFields(obj);
            if (fields == null)
            {
                return null;
            }
            string conditionStr = GetUpdateConditions(conditions);

            string sqlStr = string.Format("SELECT {0} FROM {1} WHERE {2}", fields, tableName, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            DataTable dataTable = GetDataTableSqlParameter(sqlStr, paras.ToArray());
            if (dataTable != null)
            {
                return dataTable.AsEnumerable().First<DataRow>();
            }
            return null;
        }
        #endregion

        #region select  rows by condition
        /// <summary>
        /// 获取所有数据
        /// </summary>
        /// <param name="tableName">表</param>
        /// <param name="columns">要查询的字段，为了提高查询效率，禁止用*</param>
        /// <returns></returns>
        public static DataTable GetRows(string tableName, string[] columns, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return null;
            }
            if (columns.Length == 0)
            {
                return null;
            }
            if (conditions.Count == 0)
            {
                return null;
            }
            string conditionStr = GetUpdateConditions(conditions);

            string sqlStr = string.Format("SELECT {0} FROM {1} WHERE {2}", string.Join(",", columns), tableName, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            return GetDataTableSqlParameter(sqlStr, paras.ToArray());
        }
        /// <summary>
        /// 获取所有数据
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <returns></returns>
        public static DataTable GetRows<T>(T obj, string tableName, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return null;
            }
            string fields = GetFields(obj);
            if (fields == null)
            {
                return null;
            }
            if (conditions.Count == 0)
            {
                return null;
            }
            string conditionStr = GetUpdateConditions(conditions);

            string sqlStr = string.Format("SELECT {0} FROM {1} WHERE {2}", fields, tableName, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            return GetDataTableSqlParameter(sqlStr, paras.ToArray());
        }
        #endregion


        #region select all row
        /// <summary>
        /// 获取所有数据
        /// </summary>
        /// <param name="tableName">表</param>
        /// <param name="columns">要查询的字段，为了提高查询效率，禁止用*</param>
        /// <returns></returns>
        public static DataTable GetAll(string tableName, string[] columns)
        {
            if (tableName == null)
            {
                return null;
            }
            if(columns.Length == 0)
            {
                return null;
            }
            string sqlStr = string.Format("SELECT {0} FROM {1}", string.Join(",", columns), tableName);
            return GetDataTableSql(sqlStr);
        }
        /// <summary>
        /// 获取所有数据
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <returns></returns>
        public static DataTable GetAll<T>(T obj, string tableName)
        {
            if (tableName == null)
            {
                return null;
            }
            string fields = GetFields(obj);
            if (fields == null)
            {
                return null;
            }
            string sqlStr = string.Format("SELECT {0} FROM {1}", fields, tableName);
            return GetDataTableSql(sqlStr);
        }
        #endregion


        #region select DataTable
        /// <summary>
        /// 查询 返回DataTable 对象
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <returns></returns>
        public static DataTable GetDataTableSql(String sqlStr)
        {
            return GetDataTable(sqlStr, null, CommandType.Text);
        }

        /// <summary>
        /// 查询 返回DataTable 对象
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <param name="paras">参数</param>
        /// <returns></returns>
        public static DataTable GetDataTableSqlParameter(String sqlStr, MySqlParameter[] paras)
        {
            return GetDataTable(sqlStr, paras, CommandType.Text);
        }
        /// <summary>
        ///  查询 返回DataTable 对象
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <param name="paras">参数</param>
        /// <param name="type"></param>
        /// <returns></returns>
        private static DataTable GetDataTable(string sqlStr, MySqlParameter[] paras, CommandType type)
        {
            DataTable dt = new DataTable();
            using (MySqlConnection conn = GetConn())
            {
                MySqlDataAdapter ada = new MySqlDataAdapter(sqlStr, conn);
                ada.SelectCommand.CommandType = type;
                if (paras != null)
                {
                    ada.SelectCommand.Parameters.AddRange(paras);
                }
                ada.Fill(dt);
            }
            return dt;
        }
        #endregion



        #region insert
        /// <summary>
        /// 添加单条数据
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <returns></returns>
        public static int QueryInsert<T>(T obj, string tableName)
        {
            if (tableName == null)
            {
                return 0;
            }
            string fields = GetFields(obj);
            if (fields == null)
            {
                return 0;
            }
            string values = GetValues(obj);
            if (values == null)
            {
                return 0;
            }
            // insert 语句
            string sqlStr = string.Format("INSERT INTO {0} ( {1} ) VALUES( {2} )", tableName, fields, values);
            // 字段列表
            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (var item in obj.GetType().GetProperties())
            {
                if (item.GetValue(obj) != null)
                {
                    paras.Add(SetParam("@" + item.Name, item.GetValue(obj)));
                }
            }
            return QuerySqlParameter(sqlStr, paras.ToArray());
        }
        #endregion


        #region update
        /// <summary>
        /// 根据id更新指定行数据
        /// </summary>
        /// <param name="tableName"></param>
        /// <param name="updateColumns"></param>
        /// <param name="id"></param>
        /// <returns></returns>
        public static int QueryUpdateById(string tableName, Dictionary<string, object> updateColumns, int id)
        {
            if (tableName == null)
            {
                return 0;
            }
            if (updateColumns.Count == 0)
            {
                return 0;
            }
            if (id == 0)
            {
                return 0;
            }
            string values = GetUpdateValues(updateColumns);
            string sqlStr = string.Format("UPDATE {0} SET {1} WHERE id={2}", tableName, values, id);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in updateColumns)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            return QuerySqlParameter(sqlStr, paras.ToArray());
        }
        /// <summary>
        /// 根据id更新
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <param name="id"></param>
        /// <returns></returns>
        public static int QueryUpdateById<T>(T obj, string tableName, int id)
        {
            if (tableName == null)
            {
                return 0;
            }
            if (id == 0)
            {
                return 0;
            }
            string values = GetUpdateValues(obj);
            string sqlStr = string.Format("UPDATE {0} SET {1} WHERE id={2}", tableName, values, id);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (var item in obj.GetType().GetProperties())
            {
                if (item.GetValue(obj) != null)
                {
                    paras.Add(SetParam("@" + item.Name, item.GetValue(obj)));
                }
            }

            return QuerySqlParameter(sqlStr, paras.ToArray());
        }

       /// <summary>
       /// 根据条件更新
       /// </summary>
       /// <param name="tableName"></param>
       /// <param name="updateColumns"></param>
       /// <param name="conditions"></param>
       /// <returns></returns>
        public static int QueryUpdate(string tableName, Dictionary<string, object> updateColumns, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return 0;
            }
            if (updateColumns.Count == 0)
            {
                return 0;
            }
            if (conditions.Count == 0)
            {
                return 0;
            }
            string values = GetUpdateValues(updateColumns);
            string conditionStr = GetUpdateConditions(conditions);

            string sqlStr = string.Format("UPDATE {0} SET {1} WHERE {2}", tableName, values, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in updateColumns)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            return QuerySqlParameter(sqlStr, paras.ToArray());
        }

        /// <summary>
        /// 根据条件更新
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <param name="tableName"></param>
        /// <param name="conditions"></param>
        /// <returns></returns>
        public static int QueryUpdate<T>(T obj, string tableName, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return 0;
            }
            if  (conditions.Count == 0)
            {
                return 0;
            }
            string conditionStr = GetUpdateConditions(conditions);
            string values = GetUpdateValues(obj);
            string sqlStr = string.Format("UPDATE {0} SET {1} WHERE {2}", tableName, values, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (var item in obj.GetType().GetProperties())
            {
                if (item.GetValue(obj) != null)
                {
                    paras.Add(SetParam("@" + item.Name, item.GetValue(obj)));
                }
            }

            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            return  QuerySqlParameter(sqlStr, paras.ToArray());
        }
        #endregion


        #region delete
        /// <summary>
        /// 根据id 删除
        /// </summary>
        /// <param name="tableName"></param>
        /// <param name="id"></param>
        /// <returns></returns>
        public static int QueryDeleteById(string tableName, int id)
        {
            if (tableName == null)
            {
                return 0;
            }
            if (id == 0)
            {
                return 0;
            }
            string sqlStr = string.Format("DELETE FROM {0} where id={1}", tableName, id);
            return QuerySql(sqlStr);
        }

        /// <summary>
        /// 根据条件删除
        /// </summary>
        /// <param name="tableName"></param>
        /// <param name="conditions"></param>
        /// <returns></returns>
        public static int QueryDelete(string tableName, Dictionary<string, object> conditions)
        {
            if (tableName == null)
            {
                return 0;
            }
            if (conditions.Count == 0)
            {
                return 0;
            }
            string conditionStr = GetUpdateConditions(conditions);
            string sqlStr = string.Format("DELETE FROM {0} where {1}", tableName, conditionStr);

            List<MySqlParameter> paras = new List<MySqlParameter>();
            foreach (KeyValuePair<string, object> item in conditions)
            {
                paras.Add(SetParam("@" + item.Key, item.Value));
            }

            return QuerySqlParameter(sqlStr, paras.ToArray());
        }
        #endregion


        #region insert update delete
        /// <summary>
        /// 纯sql语句
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <returns></returns>
        public static int QuerySql(string sqlStr)
        {
            return query(sqlStr, null, CommandType.Text);
        }

        /// <summary>
        /// 参数sql语句
        /// </summary>
        /// <param name="sqlStr">sql</param>
        /// <param name="paras">参数</param>
        /// <returns></returns>
        public static int QuerySqlParameter(string sqlStr, MySqlParameter[] paras)
        {
            return query(sqlStr, paras, CommandType.Text);
        }

        /// <summary>
        ///  执行sql
        /// </summary>
        /// <param name="sql_str">sql语句</param>
        /// <returns>受影响的行数</returns>
        private static int query(string sqlStr, MySqlParameter[] paras, CommandType type)
        {
            int res = 0;
            // 创建数据库连接对象
            using (MySqlConnection conn = GetConn())
            {
                // 创建操作对象
                MySqlCommand comd = conn.CreateCommand();
                // 定义操作类型
                comd.CommandType = type;
                // 定义操作语句
                comd.CommandText = sqlStr;
                // 定义执行语句参数
                if (paras != null)
                {
                    comd.Parameters.AddRange(paras);
                }
                // 打开数据库连接
                OpenConn();
                // 执行操作
                res = comd.ExecuteNonQuery();
                //关闭数据库连接,释放操作对象
                comd.Dispose();
                CloseConn();
            }
            return res;
        }
        #endregion

        #region others
        /// <summary>
        /// 实例化MySqlParameter
        /// </summary>
        /// <param name="param"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        public static MySqlParameter SetParam(string param, object value)
        {
            return new MySqlParameter(param, value);
        }

        /// <summary>
        /// 拼接insert的数据字段
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <returns></returns>
        private static string GetFields<T>(T obj)
        {
            if (obj == null)
            {
                return string.Empty;
            }
            return string.Join(",", obj.GetType().GetProperties().Select(p => p.Name).ToList());
        }

        /// <summary>
        /// 拼接insert的数据值字段
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <returns></returns>
        private static string GetValues<T>(T obj)
        {
            if (obj == null)
            {
                return string.Empty;
            }
            return string.Join(",", obj.GetType().GetProperties().Select(p => "@" + p.Name).ToList());
        }

        /// <summary>
        /// 拼接update的数据字段
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="obj"></param>
        /// <returns></returns>
        private static string GetUpdateValues<T>(T obj)
        {
            if (obj == null)
            {
                return string.Empty;
            }
            // 不更新created_at， 需要没张表都有这个字段和updated_at字段
            return string.Join(",", obj.GetType().GetProperties().Where(p => (p.Name != "id" && p.Name != "created_at")).Select(p => p.Name + "=@" + p.Name).ToList());
        }

        private static string GetUpdateValues(Dictionary<string, object> updateColumns)
        {
            if (updateColumns == null)
            {
                return string.Empty;
            }
            if (updateColumns.Count == 1)
            {
                return updateColumns.Keys + "=@" + updateColumns.Values;
            }
            List<string> items = new List<string>();
            foreach (string itemKey in updateColumns.Keys)
            {
                items.Add(itemKey + "=@" + itemKey);
            }
            return string.Join(",  ", items);
        }

        /// <summary>
        /// 拼接where语句
        /// </summary>
        /// <param name="conditions"></param>
        /// <returns></returns>
        private static string GetUpdateConditions(Dictionary<string, object> conditions)
        {
            if(conditions == null)
            {
                return string.Empty;
            }
            List<string> items = new List<string>();
            foreach (string itemKey in conditions.Keys)
            {
                items.Add(itemKey + "=@" + itemKey);
            }
            if (items.Count == 1)
            {
                return string.Join(" ", items);
            }
            return string.Join(" AND ", items);
        }
        #endregion
    }
}
