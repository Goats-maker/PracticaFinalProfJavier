using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Configuration;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SisBecasWinForms
{
    internal class Conexion
    {
        private readonly string cadena;

        public Conexion()
        {
            cadena = ConfigurationManager.ConnectionStrings["ConexionBD"].ConnectionString;
        }

        public SqlConnection ObtenerConexion()
        {
            return new SqlConnection(cadena);
        }
    }
}
