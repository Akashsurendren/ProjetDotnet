using UnityEngine;
using UnityEngine.SceneManagement;

public class GeometryControl : MonoBehaviour
{
    public float moveSpeed = 9.0f;
    public float jumpForce = 11.0f;
    private Rigidbody2D rb;
    public bool isGrounded;

    void Start()
    {
        rb = GetComponent<Rigidbody2D>();
    }

    void Update()
    {
        // Vitesse
        rb.linearVelocity = new Vector2(moveSpeed, rb.linearVelocity.y);

        // Saut avec Espace
        if (Input.GetKeyDown(KeyCode.Space) && isGrounded)
        {
            rb.linearVelocity = new Vector2(rb.linearVelocity.x, jumpForce);
            isGrounded = false;
        }
    }

    // Détection du sol
    private void OnCollisionEnter2D(Collision2D collision)
    {
        // Vérifie que ton objet dans la Hierarchy s'appelle bien "sol"
        if (collision.gameObject.name == "sol")
        {
            isGrounded = true;
        }
    }

    // Détection de la mort
    private void OnTriggerEnter2D(Collider2D other)
    {
        if (other.CompareTag("Obstacle"))
        {
            SceneManager.LoadScene(SceneManager.GetActiveScene().name);
        }
    }
}