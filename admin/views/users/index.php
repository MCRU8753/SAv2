<h3>Seznam vseh uporabnikov</h3>
<a href="?controller=users&action=create"><button class="btn btn-primary">Dodaj uporabnika</button></a>
<table>
  <thead>
    <tr>
      <th>Ime</th>
      <th>Priimek</th>
      <th>Uporabniško ime</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <!-- tukaj se sprehodimo čez array oglasov in izpisujemo vrstico posameznega oglasa-->
    <?php foreach ($users as $user) { ?>
      <tr>
        <td><?php echo $user->firstname; ?></td>
        <td><?php echo $user->lastname; ?></td>
        <td><?php echo $user->username; ?></td>
        <td>
          <!-- pri vsakem oglasu dodamo povezavo na akcije show, edit in delete, z idjem oglasa. Uporabnik lahko tako proži novo akcijo s pritiskom na gumb.-->
          <a href='?controller=users&action=show&id=<?php echo $user->id; ?>'><button class="btn btn-primary">Prikaži</button></a>
          <a href='?controller=users&action=edit&id=<?php echo $user->id; ?>'><button class="btn btn-primary">Uredi</button></a>
          <a href='?controller=users&action=delete&id=<?php echo $user->id; ?>'><button class="btn btn-danger">Izbriši</button></a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>