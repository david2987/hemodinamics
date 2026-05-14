<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src=" https://code.jquery.com/jquery-3.6.0.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
                   
       $('#FirstAlert').show();
       $('#SecondAlert').hide();

       $('#botonEliminar').click(function(){
            $('#FirstAlert').hide();
           $('#SecondAlert').show();           
       });

    });
</script>


<div class="container" align='center' id="FirstAlert" >
    <h2><span class="badge badge-primary" style="color:black">Se va a eliminar el Presupuesto Nº <b style="color:red"><?php echo $_GET['id']; ?></b> </span> </h2>
    <h3>Esta seguro desea continuar?</h2>

<br>

<div>
    <table style="width: 100%;" >
        <tr>
            <td align="center">                
                    <input type="hidden" value="<?php echo $_GET['id']; ?>">
                    <input type="button" value="Eliminar" class="btn btn-primary" id="botonEliminar" />
                    <input type="button" value="Cancelar" class="btn btn-danger" onclick="window.close();" />
            </td>
        </tr>
    </table>

</div>
</div>

<div  id="SecondAlert" align='center'>
    <div>
        <h4><span class="badge badge-primary" style="color:black">Esta realmente seguro/a de eliminar el Presupuesto Nº <b style="color:red"><?php echo $_GET['id']; ?></b> </span> </h4>
        <h5>Esta seguro desea continuar?</h2>
    </div>
    <tr>
            <td align="center">
                <form action="delete.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                    <input type="submit" value="Eliminar" class="btn btn-primary" />
                    <input type="button" value="Cancelar" class="btn btn-danger" onclick="window.close();" />
                </form>   
            </td>
        </tr>

</div>
