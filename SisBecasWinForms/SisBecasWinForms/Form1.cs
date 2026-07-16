using SisBecasWinForms.Datos;
using System;
using System.Text.RegularExpressions;
using System.Globalization;
using System.Collections.Generic;
using System.ComponentModel;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace SisBecasWinForms
{
    public partial class frmAspirantes : Form
    {
        public frmAspirantes()
        {
            InitializeComponent();
            ProbarConexion();
            CargarTiposBeca();
        }

        private void ProbarConexion()
        {
            Conexion conexion = new Conexion();

            try
            {
                using (SqlConnection cn = conexion.ObtenerConexion())
                {
                    cn.Open();

                    lblEstado.Text = "🟢 Conectado";

                    MessageBox.Show(
                        "Conexión realizada correctamente.",
                        "Sistema",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Information);
                }
            }
            catch (Exception ex)
            {
                lblEstado.Text = "🔴 Sin conexión";

                MessageBox.Show(
                    "No fue posible conectar con SQL Server.\n\n" +
                    ex.Message,
                    "Error",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Error);
            }
        }

        // Carga automáticamente los tipos de beca activos

        private void CargarTiposBeca()
        {
            Conexion conexion = new Conexion();

            try
            {
                using (SqlConnection cn = conexion.ObtenerConexion())
                {
                    cn.Open();

                    string sql = @"SELECT
                           IdTipo,
                           Nombre,
                           MontoMensual
                           FROM Tipos_Beca
                           WHERE Activo = 1
                           ORDER BY Nombre";

                    SqlCommand cmd = new SqlCommand(sql, cn);

                    SqlDataReader dr = cmd.ExecuteReader();

                    cmbTipoBeca.Items.Clear();

                    while (dr.Read())
                    {
                        TipoBeca tipo = new TipoBeca();

                        tipo.IdTipo = Convert.ToInt32(dr["IdTipo"]);
                        tipo.Nombre = dr["Nombre"].ToString();
                        tipo.MontoMensual = Convert.ToDecimal(dr["MontoMensual"]);

                        cmbTipoBeca.Items.Add(tipo);
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(
                    "Error al cargar los tipos de beca.\n\n" + ex.Message,
                    "Error",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Error);
            }
        }

        // Valida todos los campos del formulario antes de guardar
        private bool ValidarFormulario()
        {
            List<string> errores = new List<string>();

            // DUI
            if (!Regex.IsMatch(txtDui.Text.Trim(), @"^\d{8}-\d$"))
            {
                errores.Add("• El formato del DUI es incorrecto.");
            }
            
            // Nombres
            if (string.IsNullOrWhiteSpace(txtNombres.Text))
                errores.Add("• Debe ingresar los nombres.");

            // Apellidos
            if (string.IsNullOrWhiteSpace(txtApellidos.Text))
                errores.Add("• Debe ingresar los apellidos.");

            // Sexo
            if (!rbFemenino.Checked && !rbMasculino.Checked)
                errores.Add("• Debe seleccionar el sexo.");

            // Teléfono
            if (!Regex.IsMatch(txtTelefono.Text, @"^[67]\d{3}-\d{4}$"))
                errores.Add("• El teléfono debe iniciar con 6 o 7.");

            // Correo
            if (!string.IsNullOrWhiteSpace(txtCorreo.Text))
            {
                if (!Regex.IsMatch(txtCorreo.Text.Trim(),
                    @"^[^@\s]+@[^@\s]+\.[^@\s]+$"))
                {
                    errores.Add("• El correo electrónico no es válido.");
                }
            }

            // Institución
            if (txtInstitucion.Text.Trim().Length < 5)
                errores.Add("• Debe escribir una institución válida.");

            // Promedio
            if (nudPromedio.Value < 6 || nudPromedio.Value > 10)
                errores.Add("• El promedio debe estar entre 6.00 y 10.00.");

            // Ingreso
            if (nudIngreso.Value < 0)
                errores.Add("• El ingreso familiar no puede ser negativo.");

            // Tipo de beca
            if (cmbTipoBeca.SelectedIndex == -1)
                errores.Add("• Debe seleccionar un tipo de beca.");

            // Edad
            int edad = DateTime.Today.Year - dtpNacimiento.Value.Year;

            if (dtpNacimiento.Value.Date > DateTime.Today.AddYears(-edad))
                edad--;

            if (edad < 15 || edad > 30)
                errores.Add("• La edad debe estar entre 15 y 30 años.");

            if (errores.Count > 0)
            {
                MessageBox.Show(
                    string.Join(Environment.NewLine, errores),
                    "Validación",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Warning);

                return false;
            }

            return true;
        }

        private bool ValidarDui(string dui)
        {
            dui = dui.Replace("-", "");

            if (dui.Length != 9)
                return false;

            if (!long.TryParse(dui, out _))
                return false;

            int suma = 0;

            for (int i = 0; i < 8; i++)
            {
                suma += (9 - i) * int.Parse(dui[i].ToString());
            }

            int residuo = suma % 10;

            int digito = residuo == 0 ? 0 : 10 - residuo;

            return digito == int.Parse(dui[8].ToString());
        }

        private void btnGuardar_Click(object sender, EventArgs e)
        {
            if (!ValidarFormulario())
                return;

            GuardarAspirante();
        }

        // Inserta un nuevo aspirante usando parámetros
        private void GuardarAspirante()
        {
            Conexion conexion = new Conexion();

            try
            {
                using (SqlConnection cn = conexion.ObtenerConexion())
                {
                    cn.Open();

                    TipoBeca beca = (TipoBeca)cmbTipoBeca.SelectedItem;

                    string sexo = rbFemenino.Checked ? "F" : "M";

                    string sql = @"
            INSERT INTO Aspirantes
            (
                DUI,
                Nombres,
                Apellidos,
                FechaNacimiento,
                Sexo,
                Telefono,
                Correo,
                InstitucionEstudio,
                Promedio,
                IngresoFamiliar,
                IdTipo
            )

            OUTPUT INSERTED.IdAspirante

            VALUES
            (
                @dui,
                @nombres,
                @apellidos,
                @fecha,
                @sexo,
                @telefono,
                @correo,
                @institucion,
                @promedio,
                @ingreso,
                @idtipo
            )";

                    SqlCommand cmd = new SqlCommand(sql, cn);

                    cmd.Parameters.AddWithValue("@dui", txtDui.Text.Trim());

                    cmd.Parameters.AddWithValue("@nombres", txtNombres.Text.Trim());

                    cmd.Parameters.AddWithValue("@apellidos", txtApellidos.Text.Trim());

                    cmd.Parameters.AddWithValue("@fecha", dtpNacimiento.Value.Date);

                    cmd.Parameters.AddWithValue("@sexo", sexo);

                    cmd.Parameters.AddWithValue("@telefono", txtTelefono.Text);

                    cmd.Parameters.AddWithValue("@correo", txtCorreo.Text.Trim());

                    cmd.Parameters.AddWithValue("@institucion", txtInstitucion.Text.Trim());

                    cmd.Parameters.AddWithValue("@promedio", nudPromedio.Value);

                    cmd.Parameters.AddWithValue("@ingreso", nudIngreso.Value);

                    cmd.Parameters.AddWithValue("@idtipo", beca.IdTipo);

                    int idGenerado = Convert.ToInt32(cmd.ExecuteScalar());

                    lblEstado.Text = "Conectado";

                    MessageBox.Show(
                        $"Aspirante registrado correctamente.\n\nID asignado: {idGenerado}",
                        "Registro exitoso",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Information);

                    LimpiarFormulario();
                }
            }

            catch (SqlException ex)
            {
                lblEstado.Text = "Sin conexión";

                if (ex.Number == 2627 || ex.Number == 2601)
                {
                    MessageBox.Show(
                        "Ya existe un aspirante con ese DUI.",
                        "Registro duplicado",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Warning);
                }
                else
                {
                    MessageBox.Show(
                        ex.Message,
                        "Error SQL",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Error);
                }
            }

            catch (Exception ex)
            {
                MessageBox.Show(
                    ex.Message,
                    "Error",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Error);
            }
        }

        private void LimpiarFormulario()
        {
            txtDui.Clear();

            txtNombres.Clear();

            txtApellidos.Clear();

            txtTelefono.Clear();

            txtCorreo.Clear();

            txtInstitucion.Clear();

            nudPromedio.Value = 6;

            nudIngreso.Value = 0;

            dtpNacimiento.Value = DateTime.Today;

            rbFemenino.Checked = false;

            rbMasculino.Checked = false;

            cmbTipoBeca.SelectedIndex = -1;

            txtDui.Focus();
        }

        private void btnLimpiar_Click(object sender, EventArgs e)
        {
            DialogResult r = MessageBox.Show(
       "¿Desea limpiar el formulario?",
       "Confirmación",
       MessageBoxButtons.YesNo,
       MessageBoxIcon.Question);

            if (r == DialogResult.Yes)
            {
                LimpiarFormulario();
            }
        }

        private void btnCancelar_Click(object sender, EventArgs e)
        {
            DialogResult respuesta = MessageBox.Show(
       "¿Desea cerrar el formulario?",
       "Confirmación",
       MessageBoxButtons.YesNo,
       MessageBoxIcon.Question);

            if (respuesta == DialogResult.Yes)
            {
                Close();
            }
        }

        private void txtNombres_TextChanged(object sender, EventArgs e)
        {
          
        }

        private void txtApellidos_TextChanged(object sender, EventArgs e)
        {
           
        }

        private void txtNombres_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (!char.IsLetter(e.KeyChar) &&
      !char.IsControl(e.KeyChar) &&
      !char.IsWhiteSpace(e.KeyChar))
            {
                e.Handled = true;
            }
        }

        private void txtApellidos_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (!char.IsLetter(e.KeyChar) &&
       !char.IsControl(e.KeyChar) &&
       !char.IsWhiteSpace(e.KeyChar))
            {
                e.Handled = true;
            }
        }
    }
}
