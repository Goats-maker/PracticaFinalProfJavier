using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SisBecasWinForms.Datos
{
    internal class TipoBeca
    {
        public int IdTipo { get; set; }

        public string Nombre { get; set; }

        public decimal MontoMensual { get; set; }

        public override string ToString()
        {
            return $"{Nombre} - ${MontoMensual}/mes";
        }
    }
}
