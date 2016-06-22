using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using UnityEngine;
using System.Collections;

namespace Plugin
{
    public class Cliente : MonoBehaviour
    {
        private static string LoginUrl = "http://localhost/moodle/local/wsgames/game_login.php";
        private static string LoginUrlNota = "http://localhost/moodle/local/wsgames/game_score.php";
        private static string Usuario = "";
        private static string Password = "";
        private static string IdGame = "";

        private static string Token;
        private static double Nota;

        /*
    Si todo es correcto = 0
    -No usuario = 1
    -No contraseña =2
    -No idGame = 3
    -Error conexion = 4
    - Error en usuario o contraseña = 5
      */
        private int Fallo = -1;

        private bool finConexion;



        // Use this for initialization
        void Start()
        {

            finConexion = false;
        }

        // Update is called once per frame
        void Update()
        {

        }

        public bool getFinC()
        {
            return finConexion;
        }
        public int getEsExito()
        {
            return Fallo;
        }

        //Cambia la ruta del login
        public void setRutaLogin(String ruta)
        {
            LoginUrl = ruta;
        }

        //Devuelve la ruta del login
        public string getRutaLogin()
        {
            return LoginUrl;
        }

        //Cambia la ruta de subir nota
        public void setRutaNota(String ruta)
        {
            LoginUrlNota = ruta;
        }

        //Cambia la ruta de la nota
        public string getRutaNota()
        {
            return LoginUrlNota;
        }



        public void subirNota(double nota)
        {
            Nota = nota;

            Debug.Log("Plugin : Nota recibida es  " + Nota);


            StartCoroutine(upNota());

        }

        public void autentificacion(string u, string p, string i)
        {
            Debug.Log("Plugin, autentificacon : Usuario " + u + " Pass " + p + " idgame " + i);



            //Comprobamos si se encuentran todos los datos
            if (String.IsNullOrEmpty(u))
            {
                Fallo = 1;
                Debug.Log("Fallo usuario" + Fallo);
                finConexion = true;

            }
            else if (String.IsNullOrEmpty(p))
            {
                Fallo = 2;
                Debug.Log("Fallo pass" + Fallo);
                finConexion = true;
            }
            else if (String.IsNullOrEmpty(i))
            {
                Fallo = 3;
                Debug.Log("Fallo idGame" + Fallo);
                finConexion = true;
            }
            else
            {
                finConexion = false;
                //Guardamos datos de auntenticacion
                Usuario = u;
                Password = p;
                IdGame = i;

                StartCoroutine(LoginAccount());

            }

        }





        #region CoRoutines

        IEnumerator LoginAccount()
        {
            Debug.Log("<color= green >En el plugin.. Conectando...</color>");

            //Variable WWW
            WWWForm Form = new WWWForm();

            //Añadimos datos a form
            Form.AddField("username", Usuario);
            Form.AddField("password", Password);
            Form.AddField("idgame", IdGame);


            //Instanciamos www para conectarnos a la url
            WWW LoginAccountWWW = new WWW(LoginUrl, Form);

            //Split devolver elemento uno a uno
            yield return LoginAccountWWW;

            if (LoginAccountWWW.error != null)
            {
                //Salida de error
                Debug.Log("error conexion" + LoginAccountWWW.error);

                Fallo = 4;
            }
            else {

                //Si nos hemos conectado
                string LogText = LoginAccountWWW.text;

                Debug.Log(LogText);

                //Respuesta
                string[] LogTextSplit = LogText.Split(':');

                //Respuesta del servidor trato de esta forma
                if (LogTextSplit[0] == "Fail")
                {
                    Debug.Log("Plugin: Fail");
                    if (LogTextSplit[1] == "data")
                    {
                        Debug.Log("Plugin: Usuario o contraseña incorrecta");
                        Fallo = 5;
                    }
                    else if (LogTextSplit[1] == "idgame")
                    {
                        Debug.Log("Plugin: identificador de juego incorrecto");
                        Fallo = 3;
                    }
                }
                else if (LogTextSplit[0] == "Success")
                {
                    Token = LogTextSplit[1];
                    Debug.Log("El nuevo token es : " + Token);
                    Debug.Log("Plugin: Usuario existe!");
                    Fallo = 0;

                }


            }

            finConexion = true;
        }




        IEnumerator upNota()
        {
            Debug.Log("<color= green >En el plugin.. Subiendo nota...</color>");

            Debug.Log("TOKEN : " + Token + " NOTA : " + (int)Nota + " IDGAME : " + IdGame);
            //Variable WWW
            WWWForm Form = new WWWForm();

            //Añadimos datos a form4
            Form.AddField("token", Token);
            Form.AddField("gameid", IdGame);
            Form.AddField("score", (int)Nota);


            //Instanciamos www para conectarnos a la url
            WWW LoginAccountWWW = new WWW(LoginUrlNota, Form);

            //Split devolver elemento uno a uno
            yield return LoginAccountWWW;


            if (LoginAccountWWW.error != null)
            {

                Debug.LogError("Plugin : No puede loggear");
            }
            else {


                // Si nos hemos conectado
                string LogText = LoginAccountWWW.text;

                Debug.Log(LogText);
                //Quitamos el salto de linea
                LogText = LogText.Replace(Environment.NewLine, "");
                Debug.Log(LogText);

                //Respuesta
                string[] LogTextSplit = LogText.Split(':');
                Debug.Log("Split : " + LogTextSplit[0] + "  " + LogTextSplit[1]);

                if (LogTextSplit[0] == "Success")
                {
                    Debug.Log("Plugin score: Nota subida !");

                }
                else {
                    Debug.Log("Plugin score : Fallo !");
                }

            }
        }
        #endregion


    }

}
