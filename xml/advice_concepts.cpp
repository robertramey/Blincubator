#if 0

/*
#include <list>
#include <complex>
#include <algorithm>

template<typename T>
void bubble_sort(T begin, T end);

int main(){
    std::list<std::complex<float> > l;
    bubble_sort(l.begin(), l.end());
}


#include <list>
#include <complex>
#include <algorithm>

template<typename T>
void bubble_sort(T begin, T end){
    for(T i = begin; i < end; ++i)
        for(T j = i + 1; j < end; ++j)
            if(*j < *i)
                std::swap(*i, *j);
}

int main(){
    std::list<std::complex<float> > v;
    bubble_sort(v.begin(), v.end());
}
*/

#include "boost/concept/requires.hpp"
#include "boost/concept_check.hpp"
#include <iterator>
#include <utility> // swap

template <typename T>
BOOST_CONCEPT_REQUIRES((
    ((boost::ForwardIterator<T>))
    ((boost::LessThanComparable<
        typename std::iterator_traits<T>::value_type
    >))
/*
    ((boost::Swappable<
        typename std::iterator_traits<T>::value_type
    >))
*/
  ),
  (void)
)
bubble_sort(T begin, T end);
/*
{
    for(T i = begin; i < end; ++i)
        for(T j = i + 1; j < end; ++j)
            if(*j < *i)
                std::swap(*i, *j);
}
*/

#include <list>
#include <complex>

#include "boost/concept/assert.hpp"

BOOST_CONCEPT_ASSERT((
    boost::LessThanComparable<std::complex<float> >
));
/*
BOOST_CONCEPT_ASSERT((
    boost::Swappable<std::complex<float> >
));
*/

int main(){
    std::list<std::complex<float> > l;
    bubble_sort(l.begin(), l.end());
    return 0;
}
/*
#endif

#include "boost/concept/requires.hpp"
#include "boost/concept_check.hpp"
#include <iterator>

template <typename T>
BOOST_CONCEPT_REQUIRES(
    ((boost::ForwardIterator<T>))
    ((boost::LessThanComparable<
        typename std::iterator_traits<T>::value_type
    >)),
    (void)
)
bubble_sort(T begin, T end){
    for(T i = begin; i < end; ++i)
        for(T j = i + 1; j < end; ++j)
            if(*j < *i)
                std::swap(*i, *j);
}

#include <list>
#include <complex>

#include "boost/concept/assert.hpp"

int main(){
    std::list<std::complex<float> > l;
    bubble_sort(l.begin(), l.end());
    return 0;
}
*/

#endif

#include "boost/concept/usage.hpp"
#include "boost/concept/assert.hpp"

namespace boost {
template <class T>
struct Swappable {
private:
    T t1, t2; // must be data members
public:
    BOOST_CONCEPT_ASSERT((boost::Assignable<T>));
    BOOST_CONCEPT_USAGE(Swappable)
    {
        swap(t1, t2);
    }
};
} // boost

#include "boost/concept/requires.hpp"
#include "boost/concept_check.hpp"
#include <iterator>
#include <functional> // less

template <typename T, typename C>
BOOST_CONCEPT_REQUIRES(
    ((boost::ForwardIterator<T>))
    ((Swappable<T>))
    ((boost::BinaryPredicate<
        C,
        typename std::iterator_traits<T>::value_type,
        typename std::iterator_traits<T>::value_type
    >)),
    (void)
)
bubble_sort(T begin, T end, C compare);

template <typename T>
void bubble_sort(T begin, T end){
    bubble_sort(
        begin, 
        end, 
        std::less<
            typename std::iterator_traits<T>::value_type
        >()
    );
}

template<typename T>
class less2 {
    BOOST_CONCEPT_REQUIRES(
        (( boost::LessThanComparable<T>)),
        (bool)
    )
    operator()(const T & lhs, const T & rhs) const;
};

template<typename T>
class less {
    BOOST_CONCEPT_ASSERT(( boost::LessThanComparable<T> ));
    bool operator()(const T & lhs, const T & rhs) const ;
};

#include <list>
#include <complex>

int main(){
    //typedef std::complex<float> sorted_type;
    typedef int sorted_type;
    std::list<sorted_type> l;
    bubble_sort(l.begin(), l.end());
    return 0;
}

